<script setup>
const currentYear = new Date().getFullYear()
</script>

<template>
  <footer class="footer-link d-flex align-center justify-space-between">
    <span>
      © {{ currentYear }}
      <a
        href="https://themeselection.com"
        target="_blank"
        rel="noopener noreferrer"
        class="text-decoration-none font-weight-medium ms-1"
      >
        ASTA Learning Platform
      </a>
      <span class="text-body-2">. All rights reserved.</span>
    </span>
    <span class="d-md-flex gap-x-4 text-body-2">
      <a
        href="https://themeselection.com/license"
        target="_blank"
        rel="noopener noreferrer"
        class="text-decoration-none"
      >
        License
      </a>
      <a
        href="https://themeselection.com/support"
        target="_blank"
        rel="noopener noreferrer"
        class="text-decoration-none"
      >
        Support
      </a>
    </span>
  </footer>
</template>

<style lang="scss" scoped>
.footer-link {
  padding: 1.5rem;
  border-top: 0.0625rem solid rgba(var(--v-border-color), var(--v-border-opacity));
}
</style>
