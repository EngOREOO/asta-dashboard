<script setup>
import { useConfigStore } from '@/stores/config'
import { switchToVerticalNavOnLtOverlayNavBreakpoint } from '@/utils/layoutUtils'

const DefaultLayoutWithHorizontalNav = defineAsyncComponent(() => import('./components/DefaultLayoutWithHorizontalNav.vue'))
const DefaultLayoutWithVerticalNav = defineAsyncComponent(() => import('./components/DefaultLayoutWithVerticalNav.vue'))

const configStore = useConfigStore()

// Switch to vertical nav when breakpoint is reached
switchToVerticalNavOnLtOverlayNavBreakpoint()

// Loading indicator
const isFallbackStateActive = ref(false)
const refLoadingIndicator = ref(null)

watch([isFallbackStateActive, refLoadingIndicator], () => {
  if (isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.fallbackHandle()

  if (!isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.resolveHandle()
}, { immediate: true })
</script>

<template>
  <Component
    :is="configStore.appContentLayoutNav === 'vertical' ? DefaultLayoutWithVerticalNav : DefaultLayoutWithHorizontalNav"
  >
    <AppLoadingIndicator ref="refLoadingIndicator" />

    <RouterView v-slot="{ Component }">
      <Suspense
        :timeout="0"
        @fallback="isFallbackStateActive = true"
        @resolve="isFallbackStateActive = false"
      >
        <Component :is="Component" />
      </Suspense>
    </RouterView>
  </Component>
</template>

<style lang="scss">
// Layout styles
.default-layout {
  min-height: 100vh;
}
</style>
