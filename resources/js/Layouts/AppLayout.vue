<template #navigation>
  <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
      Dashboard
    </NavLink>
    <NavLink
      v-if="$page.props.auth.user.hasRole('instructor') || $page.props.auth.user.hasRole('admin')"
      :href="route('courses.index')"
      :active="route().current('courses.*')"
    >
      Courses
    </NavLink>
    <NavLink
      v-if="$page.props.auth.user.hasRole('admin')"
      :href="route('admin.dashboard')"
      :active="route().current('admin.*')"
    >
      Admin Panel
    </NavLink>
  </div>
</template>
