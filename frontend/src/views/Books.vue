<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import BookForm from '@/components/BookForm.vue';
import Pagination   from '@/components/Pagination.vue';


const items = ref({
  data: [],
  meta: null,
});
const editing = ref(null);

const fetchItems = async (p) => {
  const { data } = await api.get('/books', { params: { page: p } });
  items.value = data;
};
const fetchItem = async (id) => {
  const { data } = await api.get('/books/' + id);
  console.log('aqui',data);
  
  editing.value = { ...data.data }
  editing.value.authors_selecteds =  editing.value.authors.map(a => {
    return { id: a.id, name: a.name };
  });
  editing.value.topics_selecteds =  editing.value.topics.map(a => {
    return { id: a.id, name: a.name };
  });
  console.log('aqui',editing.value.topics_selecteds);
};

const startEdit = (item) => { 
  console.log(item.authors);
  fetchItem(item.id);
};
const startCreate = () => {
  editing.value = {
    title: '',
    subtitle: '',
    publisher: '',
    edition: '',
    year_of_publication: '',
    price: '',
    authors: [],
    authors_selecteds: [],
    topics: [],
    topics_selecteds: [],
  };
};

const remove = async (id) => {
  if (confirm('Remover?')) {
    await api.delete('/books/' + id);
    fetchItems();
  }
};

onMounted(fetchItems);
</script>

<template>
  <h1 class="h3 mb-4 text-capitalize">Livros</h1>

  <button class="btn btn-primary mb-3" @click="startCreate">Novo</button>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Titulo</th>
        <th>Ano</th>
        <th>Edição</th>
        <th>Editora</th>
        <th class="text-center">Ações</th>
      </tr>
    </thead>
    <tbody v-if="items.data && items.data.length">
      <tr v-for="i in items.data" :key="i.id">
        <td>{{ i.id }}</td>
        <td>{{ i.title }}</td>
        <td>{{ i.year_of_publication }}</td>
        <td>{{ i.edition }}ª</td>
        <td>{{ i.publisher }}</td>
        <td class="text-center">
          <button class="btn btn-sm btn-outline-secondary me-2" @click="startEdit(i)">Editar</button>
          <button class="btn btn-sm btn-outline-danger" @click="remove(i.id)">Excluir</button>
        </td>
      </tr>
    </tbody>
    <tbody v-else>
      <tr>
        <td colspan="6" class="text-center"><b>Nenhum livro encontrado</b></td>
      </tr>
    </tbody>
  </table>
  <Pagination v-if="items.meta" :meta="items.meta" @go="fetchItems" />

  <BookForm v-if="editing" :model-value="editing" @saved="fetchItems" @close="editing = null" />
</template>
