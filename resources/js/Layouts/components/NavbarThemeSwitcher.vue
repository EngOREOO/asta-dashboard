<script setup>
import { useTheme } from 'vuetify'

const { global } = useTheme()

const toggleTheme = () => {
  global.name.value = global.current.value.dark ? 'light' : 'dark'
}
</script>

<template>
  <IconBtn
    variant="text"
    color="default"
    size="small"
    @click="toggleTheme"
  >
    <VIcon
      :icon="global.current.value.dark ? 'tabler-sun' : 'tabler-moon'"
      size="20"
    />
  </IconBtn>
</template>
