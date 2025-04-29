<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import StatCard from '@/components/StatCard.vue';

const dashData = ref({
    bookCount: 0,
    topicCount: 0,
    authorCount: 0
});

const loadStats = async () => {
    const [{ data: dashboard }] = await Promise.all([
        api.get('/dashboard')
    ]);

    dashData.value = dashboard.data;
};

onMounted(loadStats);
</script>

<template>
    <h1 class="h4 mb-4">Painel - Desafio Spassu</h1>

    <div class="row g-3">
        <div class="col-12 col-md-4">
            <StatCard icon="bi-book" iconColor="text-warning" title="Livros" :count="dashData.bookCount"
                subtitle="Total de livros cadastrados" />
        </div>

        <div class="col-12 col-md-4">
            <StatCard icon="bi-tags" iconColor="text-success" title="Assuntos" :count="dashData.topicCount"
                subtitle="Diferentes temas disponíveis" />
        </div>

        <div class="col-12 col-md-4">
            <StatCard icon="bi-people" iconColor="text-primary" title="Autores" :count="dashData.authorCount"
                subtitle="Autores cadastrados" />
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <StatCard icon="bi-clipboard-data" iconColor="text-secundary" title="Livros por autores"
                subtitle="Relatório de detalhado" >
                <table striped class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Livros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(topic, index) in dashData.booksByAuthor" :key="index">
                            <td>{{ topic.name }}</td>
                            <td colspan="2">
                                <ul>
                                    <li v-for="(book, index) in topic.books" :key="index">
                                        {{ book.title }}
                                        <ul>
                                            <li v-for="(topic, index) in book.topics" :key="index">
                                                {{ topic.name }}
                                                <span v-if="index < book.topics.length - 1">, </span>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                            
                        </tr>
                    </tbody>
                </table>
            </StatCard>
        </div>
    </div>
</template>
data.