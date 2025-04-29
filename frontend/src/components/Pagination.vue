<script setup>
import { computed } from 'vue';

const props = defineProps({
  meta: { type: Object, required: true } 
});
const emit = defineEmits(['go']);

const pages = computed(() => {
  const arr = [];
  for (let p = 1; p <= props.meta.last_page; p++) arr.push(p);
  return arr;
});
</script>

<template>
  <nav v-if="meta.last_page > 1">
    <ul class="pagination justify-content-center my-3">

      <li :class="['page-item', { disabled: meta.current_page === 1 }]">
        <button class="page-link"
                @click="emit('go', meta.current_page - 1)"
                :disabled="meta.current_page === 1">«</button>
      </li>

      <li v-for="p in pages" :key="p" :class="['page-item', { active: meta.current_page === p }]">
        <button class="page-link" @click="emit('go', p)">{{ p }}</button>
      </li>

      <li :class="['page-item', { disabled: meta.current_page === meta.last_page }]">
        <button class="page-link"
                @click="emit('go', meta.current_page + 1)"
                :disabled="meta.current_page === meta.last_page">»</button>
      </li>

    </ul>
  </nav>
</template>
