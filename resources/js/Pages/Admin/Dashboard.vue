<script setup>
import { ref, onMounted } from 'vue'
import VueApexCharts from 'vue3-apexcharts'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      totalUsers: 0,
      totalStudents: 0,
      totalInstructors: 0,
      totalCourses: 0,
      publishedCourses: 0,
      pendingCourses: 0,
      totalAssessments: 0,
      totalCertificates: 0,
      pendingApplications: 0,
      totalRevenue: 0,
      averageRating: 0,
      userGrowth: 13.6,
      courseGrowth: 15.4,
      revenueGrowth: 15.4,
    })
  },
  recentActivities: {
    type: Array,
    default: () => []
  },
  pendingApprovals: {
    type: Object,
    default: () => ({ courses: [], applications: [] })
  },
  topCourses: {
    type: Array,
    default: () => []
  },
  userGrowth: {
    type: Object,
    default: () => ({ last30Days: [], totalGrowth: 0 })
  },
  courseStats: {
    type: Object,
    default: () => ({ statuses: [], byCategory: [] })
  },
  revenueData: {
    type: Object,
    default: () => ({ monthly: [], total: 0, growth: 0 })
  },
})

// Chart data
const chartData = ref({
  series: [
    {
      name: 'Revenue',
      data: props.revenueData.monthly || [30, 40, 35, 50, 49, 60, 70, 91, 125, 150, 180, 200]
    }
  ],
  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
})

const chartOptions = ref({
  chart: {
    type: 'area',
    height: 350,
    toolbar: {
      show: false
    }
  },
  colors: ['#7367F0'],
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'smooth',
    width: 2
  },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 0.2,
      stops: [0, 90, 100]
    }
  },
  xaxis: {
    categories: chartData.value.categories
  },
  yaxis: {
    labels: {
      formatter: function (val) {
        return '$' + val.toLocaleString()
      }
    }
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return '$' + val.toLocaleString()
      }
    }
  }
})

// Recent activities - use data from backend or fallback to default
const recentActivities = ref(props.recentActivities.length > 0 ? props.recentActivities.map(activity => ({
  id: activity.id,
  title: activity.title,
  description: activity.description,
  time: activity.timestamp ? new Date(activity.timestamp).toLocaleDateString() : 'Recently',
  icon: getActivityIcon(activity.type),
  color: getActivityColor(activity.type)
})) : [
  {
    id: 1,
    title: 'تم نشر دورة جديدة',
    description: 'تم نشر دورة JavaScript المتقدمة',
    time: 'منذ ساعتين',
    icon: 'tabler-book',
    color: 'success'
  },
  {
    id: 2,
    title: 'طالب جديد مسجل',
    description: 'انضم أحمد محمد إلى المنصة',
    time: 'منذ 4 ساعات',
    icon: 'tabler-user-plus',
    color: 'primary'
  },
  {
    id: 3,
    title: 'دورة مكتملة',
    description: 'أكملت فاطمة أساسيات React',
    time: 'منذ 6 ساعات',
    icon: 'tabler-certificate',
    color: 'warning'
  },
  {
    id: 4,
    title: 'تم استلام دفعة',
    description: 'تم استلام دفعة بقيمة $99 للدورة المميزة',
    time: 'منذ 8 ساعات',
    icon: 'tabler-credit-card',
    color: 'info'
  }
])

// Helper functions for activity icons and colors
function getActivityIcon(type) {
  const icons = {
    'course_created': 'tabler-book',
    'user_registered': 'tabler-user-plus',
    'instructor_application': 'tabler-file-text',
    'review_submitted': 'tabler-star',
    'course_completed': 'tabler-certificate',
    'payment_received': 'tabler-credit-card'
  }
  return icons[type] || 'tabler-activity'
}

function getActivityColor(type) {
  const colors = {
    'course_created': 'success',
    'user_registered': 'primary',
    'instructor_application': 'warning',
    'review_submitted': 'info',
    'course_completed': 'success',
    'payment_received': 'success'
  }
  return colors[type] || 'primary'
}

// Top courses - use data from backend or fallback to default
const topCourses = ref(props.topCourses.length > 0 ? props.topCourses.map(course => ({
  id: course.id,
  title: course.title,
  instructor: course.instructor,
  students: course.students_count || 0,
  rating: course.average_rating || 0,
  price: course.price || 0,
  image: '/images/asta-logo.png'
})) : [
  {
    id: 1,
    title: 'JavaScript المتقدم',
    instructor: 'أحمد محمد',
    students: 1250,
    rating: 4.8,
    price: 99,
    image: '/images/asta-logo.png'
  },
  {
    id: 2,
    title: 'أساسيات React',
    instructor: 'فاطمة علي',
    students: 980,
    rating: 4.9,
    price: 79,
    image: '/images/asta-logo.png'
  },
  {
    id: 3,
    title: 'إتقان Vue.js',
    instructor: 'محمد حسن',
    students: 750,
    rating: 4.7,
    price: 89,
    image: '/images/asta-logo.png'
  }
])

console.log('Admin Dashboard Props:', {
  stats: props.stats,
  recentActivities: props.recentActivities,
  pendingApprovals: props.pendingApprovals,
  topCourses: props.topCourses,
})
</script>

<template>
  <div>
    <!-- Welcome Card -->
    <VCard class="mb-6">
      <VCardItem>
        <VCardTitle class="text-h4 mb-2">
          مرحباً بعودتك، المدير! 👋
        </VCardTitle>
        <VCardText class="text-body-1">
          إليك ما يحدث في منصتك اليوم.
        </VCardText>
      </VCardItem>
    </VCard>

    <!-- Stats Cards -->
    <VRow>
      <VCol cols="12" sm="6" lg="3">
        <VCard>
          <VCardItem>
            <div class="d-flex justify-space-between align-center">
              <div>
                <VCardTitle class="text-h4 mb-1">{{ stats.totalUsers }}</VCardTitle>
                <VCardText class="text-body-2">إجمالي المستخدمين</VCardText>
              </div>
              <VAvatar
                size="56"
                color="primary"
                variant="tonal"
              >
                <VIcon size="28" icon="tabler-users" />
              </VAvatar>
            </div>
            <div class="d-flex align-center mt-3">
              <VIcon
                icon="tabler-trending-up"
                color="success"
                size="20"
                class="me-1"
              />
              <span class="text-success text-sm font-weight-medium">+{{ stats.userGrowth }}%</span>
              <span class="text-disabled text-sm ms-1">مقارنة بالشهر الماضي</span>
            </div>
          </VCardItem>
        </VCard>
      </VCol>

      <VCol cols="12" sm="6" lg="3">
        <VCard>
          <VCardItem>
            <div class="d-flex justify-space-between align-center">
              <div>
                <VCardTitle class="text-h4 mb-1">{{ stats.totalCourses }}</VCardTitle>
                <VCardText class="text-body-2">إجمالي الدورات</VCardText>
              </div>
              <VAvatar
                size="56"
                color="success"
                variant="tonal"
              >
                <VIcon size="28" icon="tabler-book" />
              </VAvatar>
            </div>
            <div class="d-flex align-center mt-3">
              <VIcon
                icon="tabler-trending-up"
                color="success"
                size="20"
                class="me-1"
              />
              <span class="text-success text-sm font-weight-medium">+{{ stats.courseGrowth }}%</span>
              <span class="text-disabled text-sm ms-1">مقارنة بالشهر الماضي</span>
            </div>
          </VCardItem>
        </VCard>
      </VCol>

      <VCol cols="12" sm="6" lg="3">
        <VCard>
          <VCardItem>
            <div class="d-flex justify-space-between align-center">
              <div>
                <VCardTitle class="text-h4 mb-1">${{ stats.totalRevenue }}</VCardTitle>
                <VCardText class="text-body-2">إجمالي الإيرادات</VCardText>
              </div>
              <VAvatar
                size="56"
                color="warning"
                variant="tonal"
              >
                <VIcon size="28" icon="tabler-currency-dollar" />
              </VAvatar>
            </div>
            <div class="d-flex align-center mt-3">
              <VIcon
                icon="tabler-trending-up"
                color="success"
                size="20"
                class="me-1"
              />
              <span class="text-success text-sm font-weight-medium">+{{ stats.revenueGrowth }}%</span>
              <span class="text-disabled text-sm ms-1">مقارنة بالشهر الماضي</span>
            </div>
          </VCardItem>
        </VCard>
      </VCol>

      <VCol cols="12" sm="6" lg="3">
        <VCard>
          <VCardItem>
            <div class="d-flex justify-space-between align-center">
              <div>
                <VCardTitle class="text-h4 mb-1">{{ stats.pendingApplications }}</VCardTitle>
                <VCardText class="text-body-2">الطلبات المعلقة</VCardText>
              </div>
              <VAvatar
                size="56"
                color="error"
                variant="tonal"
              >
                <VIcon size="28" icon="tabler-file-text" />
              </VAvatar>
            </div>
            <div class="d-flex align-center mt-3">
              <VIcon
                icon="tabler-clock"
                color="warning"
                size="20"
                class="me-1"
              />
              <span class="text-warning text-sm font-weight-medium">يحتاج انتباه</span>
            </div>
          </VCardItem>
        </VCard>
      </VCol>
    </VRow>

    <!-- Charts and Content -->
    <VRow class="mt-6">
      <!-- Revenue Chart -->
      <VCol cols="12" lg="8">
        <VCard>
          <VCardItem>
            <VCardTitle>نظرة عامة على الإيرادات</VCardTitle>
            <VCardText>أداء الإيرادات الشهرية</VCardText>
          </VCardItem>
          <VCardText>
            <VueApexCharts
              type="area"
              height="350"
              :options="chartOptions"
              :series="chartData.series"
            />
          </VCardText>
        </VCard>
      </VCol>

      <!-- Recent Activities -->
      <VCol cols="12" lg="4">
        <VCard>
          <VCardItem>
            <VCardTitle>النشاطات الأخيرة</VCardTitle>
            <VCardText>أحدث نشاطات المنصة</VCardText>
          </VCardItem>
          <VCardText>
            <div class="d-flex flex-column gap-4">
              <div
                v-for="activity in recentActivities"
                :key="activity.id"
                class="d-flex align-center gap-3"
              >
                <VAvatar
                  size="40"
                  :color="activity.color"
                  variant="tonal"
                >
                  <VIcon :icon="activity.icon" size="20" />
                </VAvatar>
                <div class="flex-grow-1">
                  <h6 class="text-body-1 font-weight-medium mb-1">{{ activity.title }}</h6>
                  <p class="text-body-2 text-disabled mb-1">{{ activity.description }}</p>
                  <span class="text-caption text-disabled">{{ activity.time }}</span>
                </div>
              </div>
            </div>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

    <!-- Top Courses -->
    <VRow class="mt-6">
      <VCol cols="12">
        <VCard>
          <VCardItem>
            <VCardTitle>أفضل الدورات أداءً</VCardTitle>
            <VCardText>الدورات الأكثر شعبية هذا الشهر</VCardText>
          </VCardItem>
          <VCardText>
            <VTable>
              <thead>
                <tr>
                  <th>الدورة</th>
                  <th>المحاضر</th>
                  <th>الطلاب</th>
                  <th>التقييم</th>
                  <th>السعر</th>
                  <th>الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="course in topCourses"
                  :key="course.id"
                >
                  <td>
                    <div class="d-flex align-center gap-3">
                      <VAvatar size="40">
                        <VImg :src="course.image" />
                      </VAvatar>
                      <span class="font-weight-medium">{{ course.title }}</span>
                    </div>
                  </td>
                  <td>{{ course.instructor }}</td>
                  <td>{{ course.students.toLocaleString() }}</td>
                  <td>
                    <div class="d-flex align-center gap-1">
                      <VIcon icon="tabler-star" color="warning" size="16" />
                      <span>{{ course.rating }}</span>
                    </div>
                  </td>
                  <td>${{ course.price }}</td>
                  <td>
                    <VBtn
                      size="small"
                      variant="outlined"
                      color="primary"
                    >
                      عرض التفاصيل
                    </VBtn>
                  </td>
                </tr>
              </tbody>
            </VTable>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </div>
</template>

<style scoped>
.chart-placeholder {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
