<script setup>
const isVisible = ref(false)

const fallbackHandle = () => {
  isVisible.value = true
}

const resolveHandle = () => {
  isVisible.value = false
}

defineExpose({
  fallbackHandle,
  resolveHandle,
})
</script>

<template>
  <VOverlay
    v-model="isVisible"
    class="align-center justify-center"
  >
    <VProgressCircular
      indeterminate
      color="primary"
      size="64"
    />
  </VOverlay>
</template>
