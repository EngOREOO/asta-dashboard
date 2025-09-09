<script setup>
import { ref, computed } from 'vue'
import { useDisplay } from 'vuetify'

const props = defineProps({
  navItems: {
    type: Array,
    required: true,
  },
})

const { lgAndUp } = useDisplay()

const isVerticalNavVisible = ref(lgAndUp.value)

const toggleVerticalOverlayNavActive = (val) => {
  isVerticalNavVisible.value = val
}

const isVerticalNavCollapsed = ref(false)

const toggleVerticalNavCollapsed = () => {
  isVerticalNavCollapsed.value = !isVerticalNavCollapsed.value
}

const navWidth = computed(() => {
  if (isVerticalNavCollapsed.value) return 70
  return 260
})
</script>

<template>
  <VLayout class="layout-wrapper">
    <!-- Vertical Navigation -->
    <VNavigationDrawer
      v-model="isVerticalNavVisible"
      :width="navWidth"
      :temporary="!lgAndUp"
      :permanent="lgAndUp"
      :collapsed="isVerticalNavCollapsed"
      :collapsed-width="70"
      class="layout-vertical-nav"
    >
      <!-- Logo -->
      <div class="nav-header d-flex align-center px-4 py-3">
        <VImg
          src="/logo.png"
          alt="ASTA"
          width="32"
          height="32"
          class="me-3"
        />
        <h4
          v-show="!isVerticalNavCollapsed"
          class="text-h5 font-weight-bold"
        >
          ASTA
        </h4>
      </div>

      <!-- Navigation Items -->
      <VList class="nav-list">
        <template
          v-for="item in navItems"
          :key="item.title"
        >
          <!-- Single Item -->
          <VListItem
            v-if="!item.children"
            :to="item.to"
            :value="item.title"
            class="nav-item"
          >
            <template #prepend>
              <VIcon
                :icon="item.icon.icon"
                size="20"
              />
            </template>
            <VListItemTitle
              v-show="!isVerticalNavCollapsed"
              class="nav-item-title"
            >
              {{ item.title }}
            </VListItemTitle>
          </VListItem>

          <!-- Group Item -->
          <VListGroup
            v-else
            :value="item.title"
            class="nav-group"
          >
            <template #activator="{ props: groupProps }">
              <VListItem
                v-bind="groupProps"
                class="nav-group-header"
              >
                <template #prepend>
                  <VIcon
                    :icon="item.icon.icon"
                    size="20"
                  />
                </template>
                <VListItemTitle
                  v-show="!isVerticalNavCollapsed"
                  class="nav-item-title"
                >
                  {{ item.title }}
                </VListItemTitle>
                <template #append>
                  <VIcon
                    v-show="!isVerticalNavCollapsed"
                    icon="tabler-chevron-down"
                    size="16"
                  />
                </template>
              </VListItem>
            </template>

            <VListItem
              v-for="child in item.children"
              :key="child.title"
              :to="child.to"
              :value="child.title"
              class="nav-item nav-sub-item"
            >
              <template #prepend>
                <VIcon
                  :icon="child.icon.icon"
                  size="18"
                />
              </template>
              <VListItemTitle
                v-show="!isVerticalNavCollapsed"
                class="nav-item-title"
              >
                {{ child.title }}
              </VListItemTitle>
            </VListItem>
          </VListGroup>
        </template>
      </VList>

      <!-- Collapse Button -->
      <div class="nav-footer d-flex justify-center py-2">
        <VBtn
          icon
          size="small"
          variant="text"
          @click="toggleVerticalNavCollapsed"
        >
          <VIcon
            :icon="isVerticalNavCollapsed ? 'tabler-chevron-right' : 'tabler-chevron-left'"
            size="20"
          />
        </VBtn>
      </div>
    </VNavigationDrawer>

    <!-- Main Content -->
    <VLayout>
      <!-- App Bar -->
      <VAppBar
        elevation="0"
        class="layout-navbar"
      >
        <template #prepend>
          <slot
            name="navbar"
            :toggle-vertical-overlay-nav-active="toggleVerticalOverlayNavActive"
          />
        </template>
      </VAppBar>

      <!-- Main Content -->
      <VMain class="layout-main">
        <VContainer fluid class="pa-6">
          <slot />
        </VContainer>
      </VMain>

      <!-- Footer -->
      <VFooter class="layout-footer">
        <slot name="footer" />
      </VFooter>
    </VLayout>
  </VLayout>
</template>

<style lang="scss" scoped>
.layout-wrapper {
  min-height: 100vh;
}

.layout-vertical-nav {
  border-right: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}

.nav-header {
  border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}

.nav-list {
  padding: 0;
}

.nav-item {
  margin: 0 0.5rem;
  border-radius: 0.5rem;
  
  &:hover {
    background-color: rgba(var(--v-theme-primary), 0.1);
  }
}

.nav-item-title {
  font-size: 0.875rem;
  font-weight: 500;
}

.nav-group {
  .nav-group-header {
    margin: 0 0.5rem;
    border-radius: 0.5rem;
  }
  
  .nav-sub-item {
    margin: 0 0.5rem 0 1.5rem;
    border-radius: 0.5rem;
    padding-left: 1rem;
  }
}

.nav-footer {
  border-top: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}

.layout-navbar {
  border-bottom: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}

.layout-main {
  background-color: rgb(var(--v-theme-surface));
}

.layout-footer {
  border-top: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
}
</style>
