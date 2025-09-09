<script setup>
import { useDisplay } from 'vuetify'

const { mdAndDown } = useDisplay()

const isVisible = ref(false)

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  })
}

onMounted(() => {
  window.addEventListener('scroll', () => {
    isVisible.value = window.scrollY > 300
  })
})
</script>

<template>
  <VScaleTransition>
    <VBtn
      v-show="isVisible"
      icon
      size="large"
      color="primary"
      class="scroll-to-top"
      @click="scrollToTop"
    >
      <VIcon icon="tabler-arrow-up" />
    </VBtn>
  </VScaleTransition>
</template>

<style lang="scss" scoped>
.scroll-to-top {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  z-index: 1000;
}
</style>
