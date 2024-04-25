<script setup>
    import MenuComponent from '../components/menu.vue'
    import userWelcomeComponent from '../components/userWelcome.vue'
    import ButtonComponent from '../components/button.vue'

    import axios from 'axios';
    import { ref, onMounted } from 'vue';
    import { useRouter } from 'vue-router';

    let router = useRouter();
    let city = ref([]);
    let name = ref([]);
    let citySchoolMap = ref({});
    let selectedCity = ref('');
    let selectedCitySchools = ref([]);
    let schoolSelected = ref(false);

    onMounted(() => {
        axios.get('/ManagementSchool')
            .then(response => {
                const data = response.data;
                const cities = data.map(item => item.city);
                const names = data.map(item => item.name);
                city.value = [...new Set(cities)];
                name.value = [...new Set(names)];

                data.forEach(item => {
                    if (!citySchoolMap.value[item.city]) {
                        citySchoolMap.value[item.city] = [];
                    }
                    citySchoolMap.value[item.city].push(item.name);
                });
            })
            .catch(error => {
                console.error(error);
            });
    });

    function showSchools(cityName) {
        selectedCity.value = cityName;
        selectedCitySchools.value = citySchoolMap.value[cityName];
        schoolSelected.value = true;
    }

    function navigateToSchool(cityName, schoolName) {
        console.log("Nome da cidade:", cityName);
        console.log("Nome da escola:", schoolName);
        
        router.push({ 
        name: 'SchoolDetailsByCityAndSchool', 
        params: { 
            city: cityName, 
            schoolName: schoolName 
        } 
    });
    }
</script>

<template>
    <div class="school-register">
        <userWelcomeComponent />
        <MenuComponent />
        <div v-if="!schoolSelected" class="register-content">
            <h1>Você gostaria de visualizar os dados de leitura e escrita de qual escola?</h1>
            <div class="searcheble">
                <input class="seacheble-camp" placeholder="Digite o nome do múnicipio">
                <a class="send-searche">
                    <svg width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                    Pesquisar
                </a>
            </div>

            <ButtonComponent v-for="(cityName, index) in city" :key="index" :TextValue="cityName" @click="showSchools(cityName)"/>        
        </div>

        <div v-if="schoolSelected" class="register-content">
            <h1>Você gostaria de visualizar os dados de leitura e escrita de qual escola?</h1>
            <div class="searcheble">
                <input class="seacheble-camp" placeholder="Digite o nome do múnicipio">
                <a class="send-searche">
                    <svg width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
                    Pesquisar
                </a>
            </div>
            <ButtonComponent  v-for="(schoolName, index) in selectedCitySchools" :key="index" :TextValue="schoolName" @click="navigateToSchool(selectedCity, schoolName)"/>        
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
            margin: 8rem 0;
            gap: 3rem;
            padding: 0 5rem;

            & h1 {
                color: var(--black-color);
            }
        }
    }

    @media (max-width: 900px) {
        .searcheble {
        & .seacheble-camp{
            width: 60% !important;
        }

        & .send-searche {
            width: 35%;
        }
    }
}

@media (max-width: 500px) {
    .school-register{
        & .register-content{
        padding: 0 3rem;
        gap: 3rem;

        & h1{
            text-align: center;
        }
    }
    }
        .searcheble {
            display: flex;
            flex-direction: column !important;
            margin: 0;
        & .seacheble-camp{
            width: 100% !important;
        }

        & .send-searche {
            width: 100%;
        }
    }
}
</style>