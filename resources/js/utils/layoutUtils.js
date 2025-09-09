import { useDisplay } from 'vuetify'
import { useConfigStore } from '@/stores/config'

export const switchToVerticalNavOnLtOverlayNavBreakpoint = () => {
  const { lgAndUp } = useDisplay()
  const configStore = useConfigStore()

  watch(lgAndUp, (newVal) => {
    if (!newVal) {
      configStore.appContentLayoutNav = 'vertical'
    }
  })
}

export const useLayoutConfig = () => {
  const configStore = useConfigStore()
  
  return {
    isVerticalNav: computed(() => configStore.appContentLayoutNav === 'vertical'),
    isHorizontalNav: computed(() => configStore.appContentLayoutNav === 'horizontal'),
    isBoxed: computed(() => configStore.appContentWidth === 'boxed'),
    isFluid: computed(() => configStore.appContentWidth === 'fluid'),
  }
}
