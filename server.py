#!/usr/bin/env python3
"""
Simple HTTP server to serve the ASTA login page on port 3000
"""

import http.server
import socketserver
import os
import sys
from urllib.parse import urlparse

PORT = 3000

class CORSHTTPRequestHandler(http.server.SimpleHTTPRequestHandler):
    def end_headers(self):
        # Add CORS headers
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        super().end_headers()
    
    def do_OPTIONS(self):
        self.send_response(200)
        self.end_headers()
    
    def do_GET(self):
        # Serve login.html for root path
        if self.path == '/' or self.path == '/index.html':
            self.path = '/login.html'
        super().do_GET()

def main():
    try:
        # Change to the directory containing this script
        os.chdir(os.path.dirname(os.path.abspath(__file__)))
        
        # Check if login.html exists
        if not os.path.exists('login.html'):
            print("❌ Error: login.html not found!")
            print("Make sure login.html is in the same directory as this script.")
            sys.exit(1)
        
        # Create server
        with socketserver.TCPServer(("", PORT), CORSHTTPRequestHandler) as httpd:
            print(f"🚀 Login server running on http://localhost:{PORT}")
            print(f"📱 Open your browser and go to: http://localhost:{PORT}")
            print(f"🔗 API Endpoint: https://asta.pctobia.com/public/api/login")
            print(f"👤 Test Credentials: instructor@asta.com / password")
            print(f"\nPress Ctrl+C to stop the server")
            
            try:
                httpd.serve_forever()
            except KeyboardInterrupt:
                print("\n🛑 Shutting down server...")
                httpd.shutdown()
                print("✅ Server stopped")
                
    except OSError as e:
        if e.errno == 48:  # Address already in use
            print(f"❌ Error: Port {PORT} is already in use!")
            print(f"Try using a different port or stop the process using port {PORT}")
        else:
            print(f"❌ Error: {e}")
        sys.exit(1)
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
