import { VerticalNavLayout } from './components/VerticalNavLayout.vue'

export {
  VerticalNavLayout,
}

export default {
  install: (app) => {
    app.component('VerticalNavLayout', VerticalNavLayout)
  },
}
