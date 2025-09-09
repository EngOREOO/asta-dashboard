<script setup>
const languages = [
  {
    title: 'English',
    code: 'en',
    flag: '🇺🇸',
  },
  {
    title: 'Arabic',
    code: 'ar',
    flag: '🇸🇦',
  },
]

const selectedLanguage = ref(languages[0])
</script>

<template>
  <VMenu
    location="bottom end"
    offset="8px"
    transition="scale-transition"
  >
    <template #activator="{ props }">
      <VBtn
        variant="text"
        v-bind="props"
      >
        <span class="me-2">{{ selectedLanguage.flag }}</span>
        <span class="d-none d-md-block">{{ selectedLanguage.code.toUpperCase() }}</span>
        <VIcon
          icon="tabler-chevron-down"
          size="16"
        />
      </VBtn>
    </template>

    <VCard min-width="200">
      <VList>
        <VListItem
          v-for="lang in languages"
          :key="lang.code"
          :value="lang.code"
          @click="selectedLanguage = lang"
        >
          <template #prepend>
            <span class="me-2">{{ lang.flag }}</span>
          </template>
          <VListItemTitle>{{ lang.title }}</VListItemTitle>
        </VListItem>
      </VList>
    </VCard>
  </VMenu>
</template>
