<script setup>
    import MenuComponent from '../components/menu.vue'
    import userWelcomeComponent from '../components/userWelcome.vue'
    import ButtonComponent from '../components/button.vue'

    import axios from 'axios';
    import { ref, onMounted } from 'vue';

    let cities = ref([]);
    let counties = ref([]);

    onMounted(async () => {
        try {
            const managementSchoolResponse = await axios.get('/ManagementSchool');
            const managementSchoolData = managementSchoolResponse.data;
            const managementSchoolCities = managementSchoolData.map(item => item.city);

            const countiesResponse = await axios.get('/counties');
            counties.value = countiesResponse.data;

            cities.value = managementSchoolCities.map(cityName => {
                const county = counties.value.find(county => county.id === parseInt(cityName));
                if (county) {
                    return county.name; 
                } else {
                    console.error(`Cidade não encontrada para o ID: ${cityName}`);
                    return null;
                }
            });
        } catch (error) {
            console.error('Erro ao buscar os dados:', error);
        }
    });
</script>

<template>
    <div class="school-register">
        <userWelcomeComponent />
        <MenuComponent />
        <div class="register-content">
            <h1>Você gostaria de visualizar os dados de leitura e escrita de qual escola?</h1>
            <div class="searcheble">
                <input class="seacheble-camp" placeholder="Digite o nome do município">
                <a class="send-searche">
                    <svg width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                    Pesquisar
                </a>
            </div>

            <ButtonComponent v-for="(cityName, index) in cities" :key="index" :TextValue="cityName" />        
        </div>
    </div>
</template>
<style scoped>
    .searcheble {
        display: flex;
        align-items: center;
        gap: 5%;
        width: 100%;
        & .seacheble-camp{
            padding: 1rem;
            width: 80%;
            border: 2px solid var(--black-color);
            border-radius: 4rem;
            outline: 0;
            font-size: 14px;
        }

        & .send-searche {
            width: 15%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            border: 2px solid var(--black-color);
            text-align: center;
            border-radius: 4rem;
            background-color: var(--white-color);
            color: var(--black-color);
            padding: 1rem;
        }
    }

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
            gap: 3rem;

            & h1 {
                color: var(--black-color);
            }
        }
    }
</style>