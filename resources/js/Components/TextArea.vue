<template>
  <textarea
    v-bind="$attrs"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
  ></textarea>
</template>

<script setup>
defineProps(['modelValue']);
defineEmits(['update:modelValue']);
</script>