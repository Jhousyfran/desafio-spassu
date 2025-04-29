<script setup>
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';
import { ref, reactive, onMounted } from 'vue';
import api from '@/services/api';

const props = defineProps({ modelValue: Object });
const emit = defineEmits(['saved', 'close']);
const configMoney = {
  decimal: ',',
  thousands: '.',
  prefix: 'R$ ',
  suffix: '',
  precision: 2,
  masked: false
};
const topicsData = ref([]);
const authorsData = ref([]);
const loadStats = async () => {
  const [{ data: topics }, { data: authors }] = await Promise.all([
    api.get('/topics', { params: { page: 100 } }),
    api.get('/authors', { params: { page: 100 } }),
  ]);

  topicsData.value = topics.data;
  authorsData.value = authors.data;
};
const form = reactive({ ...props.modelValue });
const errors = reactive({});
const save = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);

  form.authors = form.authors_selecteds ? form.authors_selecteds.map(a => a.id) : [];
  form.topics = form.topics_selecteds ? form.topics_selecteds.map(a => a.id) : [];

  try {
    if (form.id) {
      await api.put('/books/' + form.id, form);
    } else {
      await api.post('/books', form);
    }
    emit('saved');
    emit('close');
  } catch (err) {
    if (err.validationErrors) Object.assign(errors, err.validationErrors);
    else alert('Erro inesperado');
  }
};
onMounted(loadStats);
</script>

<template>
  <div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class=" modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ form.id ? 'Editar' : 'Novo' }} Livro</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <label class="form-label text-capitalize font-weight-bold">Titulo <b class="text-danger">*</b> </label>
              <input v-model="form.title" type="text" class="form-control" />
              <div v-if="errors.title" class="invalid-feedback d-block">
                {{ errors.title[0] }}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <label class="form-label text-capitalize font-weight-bold">Subtitulo</label>
              <input v-model="form.subtitle" type="text" class="form-control" />
              <div v-if="errors.subtitle" class="invalid-feedback d-block">
                {{ errors.subtitle[0] }}
              </div>
            </div>
            <div class="col-6">
              <label class="form-label text-capitalize font-weight-bold">Editora <b class="text-danger">*</b></label>
              <input v-model="form.publisher" type="text" class="form-control" />
              <div v-if="errors.publisher" class="invalid-feedback d-block">
                {{ errors.publisher[0] }}
              </div>
            </div>
            <div class="col-4">
              <label class="form-label text-capitalize font-weight-bold">Edição <b class="text-danger">*</b></label>
              <input v-model="form.edition" type="number" min="1" class="form-control" />
              <div v-if="errors.edition" class="invalid-feedback d-block">
                {{ errors.edition[0] }}
              </div>
            </div>
            <div class="col-4">
              <label class="form-label text-capitalize font-weight-bold">Ano <b class="text-danger">*</b></label>
              <input v-model="form.year_of_publication" type="text" max="4" class="form-control" />
              <div v-if="errors.year_of_publication" class="invalid-feedback d-block">
                {{ errors.year_of_publication[0] }}
              </div>
            </div>
            <div class="col-4">
              <label class="form-label text-capitalize font-weight-bold">Preço <b class="text-danger">*</b></label>
              <money3 v-model.number="form.price" inputmode="numeric" type="text" v-bind:="configMoney"
                class="form-control" />
              <div v-if="errors.price" class="invalid-feedback d-block">
                {{ errors.price[0] }}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="mb-3">
              <label class="form-label text-capitalize font-weight-bold">Autores <b class="text-danger">*</b></label>
              <Multiselect v-model="form.authors_selecteds" :options="authorsData" track-by="id" label="name"
                placeholder="Selecione um ou mais autores" multiple close-on-select="false" :taggable="false"
                class="form-control p-0" />

              <!-- <div v-if="errors.author_ids" class="invalid-feedback d-block">
                {{ errors.author_ids[0] }}
              </div>
              <input v-model="form.authors" type="text" class="form-control" /> -->
              <div v-if="errors.authors" class="invalid-feedback d-block">
                {{ errors.authors[0] }}
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label text-capitalize font-weight-bold">Assuntos <b class="text-danger">*</b></label>
              <Multiselect v-model="form.topics_selecteds" :options="topicsData" track-by="id" label="name"
                placeholder="Selecione um ou mais assuntos" multiple close-on-select="false" :taggable="false"
                class="form-control p-0" />
              <!-- <input v-model="form.topics" type="text" class="form-control" /> -->
              <div v-if="errors.topics" class="invalid-feedback d-block">
                {{ errors.topics[0] }}
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button type="button" class="btn btn-primary" @click="save">Salvar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-backdrop fade show"></div>
</template>
