<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import AuthorForm from '@/components/AuthorForm.vue';
import Pagination   from '@/components/Pagination.vue';


const items = ref({
  data: [],
  meta: null,
});
const editing = ref(null);

const fetchItems = async (p = 1) => {
  const { data } = await api.get('/authors', { params: { page: p } });
  items.value = data;
};

const startEdit = (item) => { editing.value = { ...item }; };
const startCreate = () => { editing.value = { name: '' }; };

const remove = async (id) => {
  if (confirm('Remover?')) {
    await api.delete('/authors/' + id);
    fetchItems();
  }
};

onMounted(fetchItems);
</script>

<template>
  <h1 class="h3 mb-4 text-capitalize">Autores</h1>

  <button class="btn btn-primary mb-3" @click="startCreate">Novo</button>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th class="text-center">Ações</th>
      </tr>
    </thead>
    <tbody v-if="items.data && items.data.length">
      <tr v-for="i in items.data" :key="i.id">
        <td>{{ i.id }}</td>
        <td>{{ i.name }}</td>
        <td class="text-center">
          <button class="btn btn-sm btn-outline-secondary me-2" @click="startEdit(i)">Editar</button>
          <button class="btn btn-sm btn-outline-danger" @click="remove(i.id)">Excluir</button>
        </td>
      </tr>
    </tbody>
  </table>
  <Pagination v-if="items.meta" :meta="items.meta" @go="fetchItems" />
  <AuthorForm v-if="editing" :model-value="editing" @saved="fetchItems" @close="editing = null" />
</template>
