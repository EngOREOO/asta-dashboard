import { defineStore } from 'pinia'

export const useConfigStore = defineStore('config', () => {
  const isAppRTL = ref(false)
  const appContentLayoutNav = ref('vertical')
  const appContentWidth = ref('boxed')
  const appContentClass = ref('')
  const appOverlay = ref(false)
  const appRTL = ref(false)
  const appTheme = ref('light')
  const appUserTheme = ref('light')
  const appBarBlur = ref(false)
  const appBarCollapsed = ref(false)
  const appBarDetached = ref(false)
  const appBarFlat = ref(false)
  const appBarFloating = ref(false)
  const appBarHidden = ref(false)
  const appBarInverted = ref(false)
  const appBarTitle = ref('ASTA Admin')
  const appBarUserMenuStyle = ref('default')
  const appBarNavbarType = ref('sticky')
  const appBarColor = ref('primary')
  const appBarElevation = ref(4)
  const appBarDensity = ref('default')
  const appBarScrollBehavior = ref('elevate')
  const appBarScrollTarget = ref('body')
  const appBarScrollThreshold = ref(100)
  const appBarScrollHide = ref(false)
  const appBarScrollShow = ref(false)
  const appBarScrollOffset = ref(0)
  const appBarScrollOffsetTop = ref(0)
  const appBarScrollOffsetBottom = ref(0)
  const appBarScrollOffsetLeft = ref(0)
  const appBarScrollOffsetRight = ref(0)
  const appBarScrollOffsetX = ref(0)
  const appBarScrollOffsetY = ref(0)
  const appBarScrollOffsetZ = ref(0)
  const appBarScrollOffsetW = ref(0)
  const appBarScrollOffsetH = ref(0)
  const appBarScrollOffsetV = ref(0)
  const appBarScrollOffsetA = ref(0)
  const appBarScrollOffsetB = ref(0)
  const appBarScrollOffsetC = ref(0)
  const appBarScrollOffsetD = ref(0)
  const appBarScrollOffsetE = ref(0)
  const appBarScrollOffsetF = ref(0)
  const appBarScrollOffsetG = ref(0)
  const appBarScrollOffsetH2 = ref(0)
  const appBarScrollOffsetI = ref(0)
  const appBarScrollOffsetJ = ref(0)
  const appBarScrollOffsetK = ref(0)
  const appBarScrollOffsetL = ref(0)
  const appBarScrollOffsetM = ref(0)
  const appBarScrollOffsetN = ref(0)
  const appBarScrollOffsetO = ref(0)
  const appBarScrollOffsetP = ref(0)
  const appBarScrollOffsetQ = ref(0)
  const appBarScrollOffsetR = ref(0)
  const appBarScrollOffsetS = ref(0)
  const appBarScrollOffsetT = ref(0)
  const appBarScrollOffsetU = ref(0)
  const appBarScrollOffsetV2 = ref(0)
  const appBarScrollOffsetW2 = ref(0)
  const appBarScrollOffsetX2 = ref(0)
  const appBarScrollOffsetY2 = ref(0)
  const appBarScrollOffsetZ2 = ref(0)

  return {
    isAppRTL,
    appContentLayoutNav,
    appContentWidth,
    appContentClass,
    appOverlay,
    appRTL,
    appTheme,
    appUserTheme,
    appBarBlur,
    appBarCollapsed,
    appBarDetached,
    appBarFlat,
    appBarFloating,
    appBarHidden,
    appBarInverted,
    appBarTitle,
    appBarUserMenuStyle,
    appBarNavbarType,
    appBarColor,
    appBarElevation,
    appBarDensity,
    appBarScrollBehavior,
    appBarScrollTarget,
    appBarScrollThreshold,
    appBarScrollHide,
    appBarScrollShow,
    appBarScrollOffset,
    appBarScrollOffsetTop,
    appBarScrollOffsetBottom,
    appBarScrollOffsetLeft,
    appBarScrollOffsetRight,
    appBarScrollOffsetX,
    appBarScrollOffsetY,
    appBarScrollOffsetZ,
    appBarScrollOffsetW,
    appBarScrollOffsetH,
    appBarScrollOffsetV,
    appBarScrollOffsetA,
    appBarScrollOffsetB,
    appBarScrollOffsetC,
    appBarScrollOffsetD,
    appBarScrollOffsetE,
    appBarScrollOffsetF,
    appBarScrollOffsetG,
    appBarScrollOffsetH2,
    appBarScrollOffsetI,
    appBarScrollOffsetJ,
    appBarScrollOffsetK,
    appBarScrollOffsetL,
    appBarScrollOffsetM,
    appBarScrollOffsetN,
    appBarScrollOffsetO,
    appBarScrollOffsetP,
    appBarScrollOffsetQ,
    appBarScrollOffsetR,
    appBarScrollOffsetS,
    appBarScrollOffsetT,
    appBarScrollOffsetU,
    appBarScrollOffsetV2,
    appBarScrollOffsetW2,
    appBarScrollOffsetX2,
    appBarScrollOffsetY2,
    appBarScrollOffsetZ2,
  }
})

export const initConfigStore = () => {
  const configStore = useConfigStore()
  return configStore
}
