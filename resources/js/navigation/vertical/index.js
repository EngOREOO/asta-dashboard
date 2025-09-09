export default [
  {
    title: 'Dashboard',
    to: { name: 'admin.dashboard' },
    icon: { icon: 'tabler-smart-home' },
  },
  {
    title: 'User Management',
    icon: { icon: 'tabler-users' },
    children: [
      {
        title: 'All Users',
        to: { name: 'admin.users' },
        icon: { icon: 'tabler-user' },
      },
      {
        title: 'Students',
        to: { name: 'admin.students' },
        icon: { icon: 'tabler-user-star' },
      },
      {
        title: 'Instructors',
        to: { name: 'admin.instructors' },
        icon: { icon: 'tabler-user-check' },
      },
    ],
  },
  {
    title: 'Course Management',
    icon: { icon: 'tabler-book' },
    children: [
      {
        title: 'All Courses',
        to: { name: 'admin.courses' },
        icon: { icon: 'tabler-book-open' },
      },
      {
        title: 'Categories',
        to: { name: 'admin.categories' },
        icon: { icon: 'tabler-category' },
      },
      {
        title: 'Materials',
        to: { name: 'admin.materials' },
        icon: { icon: 'tabler-file-text' },
      },
    ],
  },
  {
    title: 'Applications',
    to: { name: 'admin.applications' },
    icon: { icon: 'tabler-file-description' },
  },
  {
    title: 'Assessments',
    to: { name: 'admin.assessments' },
    icon: { icon: 'tabler-clipboard-check' },
  },
  {
    title: 'Certificates',
    to: { name: 'admin.certificates' },
    icon: { icon: 'tabler-certificate' },
  },
  {
    title: 'Analytics',
    icon: { icon: 'tabler-chart-bar' },
    children: [
      {
        title: 'Revenue',
        to: { name: 'admin.analytics.revenue' },
        icon: { icon: 'tabler-currency-dollar' },
      },
      {
        title: 'User Growth',
        to: { name: 'admin.analytics.users' },
        icon: { icon: 'tabler-trending-up' },
      },
      {
        title: 'Course Performance',
        to: { name: 'admin.analytics.courses' },
        icon: { icon: 'tabler-chart-line' },
      },
    ],
  },
  {
    title: 'Settings',
    icon: { icon: 'tabler-settings' },
    children: [
      {
        title: 'General',
        to: { name: 'admin.settings.general' },
        icon: { icon: 'tabler-cog' },
      },
      {
        title: 'Security',
        to: { name: 'admin.settings.security' },
        icon: { icon: 'tabler-shield' },
      },
      {
        title: 'Notifications',
        to: { name: 'admin.settings.notifications' },
        icon: { icon: 'tabler-bell' },
      },
    ],
  },
]
