<script setup>
    import MenuComponent from '../components/menu.vue'
    import TitleComponent from '../components/title.vue'
    import userWelcomeComponent from '../components/userWelcome.vue'
    import TableComponent from '../components/table.vue'

    import axios from 'axios';
        import { ref } from 'vue';

        const id = ref([]);
        const escolas = ref([]);

        axios.get('/ManagementSchool')
            .then(response => {
                const data = response.data;
                id.value = data.map(item => item.id);
                escolas.value = data.map(item => item.name);
            })
            .catch(error => {
                console.error(error);
    });
</script>

<template>
    <div class="school-register">
        <userWelcomeComponent />
        <MenuComponent />
        <div class="register-content">
            <TitleComponent title="Cadastro de escolas" />
            <TableComponent TitleValue="Escolas cadastradas"/>
        </div>
    </div>
</template>

<style scoped>
    .school-register{
        display: flex;
        height: 100vh;
        width: 100%;

        & .register-content {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 8rem;
        }
    }
</style>