<script setup>
import { reactive } from 'vue';
import api from '@/services/api';

const props = defineProps({ modelValue: Object });
const emit = defineEmits(['saved', 'close']);

const form = reactive({ ...props.modelValue });
const errors = reactive({});
const save = async () => {
  Object.keys(errors).forEach(k => delete errors[k]);
  try {
    if (form.id) {
      await api.put('/authors/' + form.id, form);
    } else {
      await api.post('/authors', form);
    }
    emit('saved');
    emit('close');
  } catch (err) {
    if (err.validationErrors) Object.assign(errors, err.validationErrors);
    else alert('Erro inesperado');
  }
};
</script>

<template>
  <div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ form.id ? 'Editar' : 'Novo' }} Autor</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label text-capitalize">Nome</label>
            <input v-model="form.name" type="text" class="form-control" />
            <div v-if="errors.name" class="invalid-feedback d-block">
              {{ errors.name[0] }}
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
