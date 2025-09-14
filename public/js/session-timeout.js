// Session Timeout Manager
class SessionTimeoutManager {
    constructor() {
        this.timeoutMinutes = 30; // Default 30 minutes
        this.warningMinutes = 5; // Show warning 5 minutes before timeout
        this.checkInterval = 60000; // Check every minute
        this.activityInterval = 30000; // Send activity ping every 30 seconds
        
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.isActive = true;
        
        this.init();
    }
    
    init() {
        // Set up activity detection
        this.setupActivityDetection();
        
        // Start the timeout checker
        this.startTimeoutChecker();
        
        // Start activity ping
        this.startActivityPing();
        
        // Listen for video events
        this.setupVideoActivityDetection();
    }
    
    setupActivityDetection() {
        const events = [
            'mousedown', 'mousemove', 'keypress', 'scroll', 
            'touchstart', 'click', 'keydown'
        ];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, true);
        });
    }
    
    setupVideoActivityDetection() {
        // Monitor all video elements
        const videos = document.querySelectorAll('video');
        
        videos.forEach(video => {
            // Activity during video playback
            video.addEventListener('play', () => {
                this.updateActivity();
                this.startVideoActivityMonitoring(video);
            });
            
            video.addEventListener('pause', () => {
                this.stopVideoActivityMonitoring(video);
            });
            
            video.addEventListener('ended', () => {
                this.stopVideoActivityMonitoring(video);
            });
        });
        
        // Monitor for dynamically added videos
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        const videos = node.querySelectorAll ? node.querySelectorAll('video') : [];
                        videos.forEach(video => {
                            this.setupVideoEvents(video);
                        });
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    setupVideoEvents(video) {
        video.addEventListener('play', () => {
            this.updateActivity();
            this.startVideoActivityMonitoring(video);
        });
        
        video.addEventListener('pause', () => {
            this.stopVideoActivityMonitoring(video);
        });
        
        video.addEventListener('ended', () => {
            this.stopVideoActivityMonitoring(video);
        });
    }
    
    startVideoActivityMonitoring(video) {
        if (video.sessionInterval) return;
        
        video.sessionInterval = setInterval(() => {
            if (!video.paused && !video.ended) {
                this.updateActivity();
            }
        }, 10000); // Update every 10 seconds during video playback
    }
    
    stopVideoActivityMonitoring(video) {
        if (video.sessionInterval) {
            clearInterval(video.sessionInterval);
            video.sessionInterval = null;
        }
    }
    
    updateActivity() {
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.isActive = true;
        
        // Send activity ping to server
        this.sendActivityPing();
    }
    
    sendActivityPing() {
        // Send AJAX request to update server-side activity
        fetch('/session/activity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                timestamp: this.lastActivity
            })
        }).catch(error => {
            console.log('Activity ping failed:', error);
        });
    }
    
    startActivityPing() {
        setInterval(() => {
            if (this.isActive) {
                this.sendActivityPing();
            }
        }, this.activityInterval);
    }
    
    startTimeoutChecker() {
        setInterval(() => {
            this.checkTimeout();
        }, this.checkInterval);
    }
    
    checkTimeout() {
        const now = Date.now();
        const inactiveTime = now - this.lastActivity;
        const inactiveMinutes = Math.floor(inactiveTime / 60000);
        
        // Show warning if approaching timeout
        if (inactiveMinutes >= (this.timeoutMinutes - this.warningMinutes) && !this.warningShown) {
            this.showWarning();
        }
        
        // Force logout if timeout exceeded
        if (inactiveMinutes >= this.timeoutMinutes) {
            this.forceLogout();
        }
    }
    
    showWarning() {
        this.warningShown = true;
        
        // Create warning modal
        const modal = document.createElement('div');
        modal.id = 'session-warning-modal';
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-clock text-2xl text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">تحذير انتهاء الجلسة</h3>
                        <p class="text-sm text-gray-600">ستنتهي جلستك قريباً</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-4">
                    لم تقم بأي نشاط خلال ${this.warningMinutes} دقائق الماضية. 
                    ستتم إعادة توجيهك إلى صفحة تسجيل الدخول خلال ${this.warningMinutes} دقائق.
                </p>
                <div class="flex justify-end space-x-3 rtl:space-x-reverse">
                    <button id="extend-session" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        تمديد الجلسة
                    </button>
                    <button id="logout-now" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        تسجيل الخروج الآن
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        document.getElementById('extend-session').addEventListener('click', () => {
            this.updateActivity();
            this.hideWarning();
        });
        
        document.getElementById('logout-now').addEventListener('click', () => {
            this.forceLogout();
        });
    }
    
    hideWarning() {
        const modal = document.getElementById('session-warning-modal');
        if (modal) {
            modal.remove();
        }
    }
    
    forceLogout() {
        // Show logout message
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-logout text-2xl text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">انتهت الجلسة</h3>
                        <p class="text-sm text-gray-600">تم تسجيل الخروج تلقائياً</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-4">
                    انتهت جلستك بسبب عدم النشاط. سيتم إعادة توجيهك إلى صفحة تسجيل الدخول.
                </p>
                <div class="flex justify-center">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        تسجيل الدخول
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Redirect to login after 3 seconds
        setTimeout(() => {
            window.location.href = '/login';
        }, 3000);
    }
    
    // Public method to manually extend session
    extendSession() {
        this.updateActivity();
        this.hideWarning();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize for authenticated users
    if (document.body.classList.contains('authenticated') || 
        document.querySelector('meta[name="user-authenticated"]')) {
        window.sessionTimeoutManager = new SessionTimeoutManager();
    }
});

// Global function to extend session (can be called from other scripts)
window.extendSession = function() {
    if (window.sessionTimeoutManager) {
        window.sessionTimeoutManager.extendSession();
    }
};
