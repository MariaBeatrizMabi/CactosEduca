<script setup>
import MenuComponent from '../components/menu.vue';
import userWelcomeComponent from '../components/userWelcome.vue';
import ButtonComponent from '../components/button.vue';
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const citiesSchools = ref([]);
const cities = ref([]);
const selectedCity = ref('');
const schoolSelected = ref(false);
const selectedSchools = ref([]);
const search = ref('');

onMounted(async () => {
    try {
        const { data } = await axios.get('/ManagementSchool/all');
        citiesSchools.value = data;
        cities.value = Array.from(new Set(data.map(item => item.city)));
    } catch (error) {
        console.error('Error fetching data:', error);
    }
    console.log(citiesSchools.value, 'aaaaaaaaaaaaaaa')

});

function showSchools(cityName) {
    search.value = '';
    selectedCity.value = cityName;
    schoolSelected.value = true;

    const cityData = citiesSchools.value.find(({ city }) => city === selectedCity.value);
    selectedSchools.value = cityData ? cityData.schools : [];
}

function navigateToSchool(cityName, schoolName) {
    if (Array.isArray(selectedSchools.value)) {
        const selectedSchool = selectedSchools.value.find(school => school.name === schoolName);
        if (selectedSchool) {
            // Armazenar o filtro no localStorage
            localStorage.setItem('selectedFilter', JSON.stringify({ filterType: 'Specific School', city: cityName, school: schoolName }));

            router.push({
                name: 'SchoolDetailsByCityAndSchool',
                params: {
                    city: cityName,
                    schoolName: schoolName,
                    schoolId: selectedSchool.id
                }
            });
        } else {
            console.error('School not found:', schoolName);
        }
    } else {
        console.error('selectedSchools is not an array:', selectedSchools.value);
    }
}


const filteredCities = computed(() => {
    return !search.value
        ? cities.value
        : cities.value.filter(name => name.toLowerCase().includes(search.value.toLowerCase()));
});

const filteredSchools = computed(() => {
    return !search.value
        ? selectedSchools.value
        : selectedSchools.value.filter(({ name }) => name.toLowerCase().includes(search.value.toLowerCase()));
});

function selectAllCities() {
    search.value = '';
    selectedCity.value = '';
    schoolSelected.value = false;

    // Armazenar o filtro no localStorage
    localStorage.setItem('selectedFilter', JSON.stringify({ filterType: 'All Cities' }));

    router.push({ name: 'SchoolDetailsAll' });
}

function selectAllSchools() {
    if (selectedCity.value) {
        // Armazenar o filtro no localStorage
        localStorage.setItem('selectedFilter', JSON.stringify({ filterType: 'All Schools in City', city: selectedCity.value }));

        router.push({
            name: 'SchoolDetailsAllByCity',
            params: { city: selectedCity.value }
        });
    } else {
        console.error('Selected city is not defined');
    }
}
</script>

<template>
    <div class="school-register">
        <userWelcomeComponent />
        <MenuComponent />

        <div v-if="!schoolSelected" class="register-content">
            <h1>Você gostaria de visualizar os dados de leitura e escrita de qual escola?</h1>
            <div class="searcheble">
                <input class="seacheble-camp" placeholder="Digite o nome do município" :value="search"
                    @input="search = $event.target.value">
                <a class="send-searche">
                    <svg width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                    Pesquisar
                </a>
            </div>

            <ButtonComponent TextValue="Selecionar Todas" @click="selectAllCities" />

            <ButtonComponent v-for="(cityName, index) in filteredCities" :key="index" :TextValue="cityName"
                @click="showSchools(cityName)" />
        </div>

        <div v-if="schoolSelected" class="register-content">
            <h1>Você gostaria de visualizar os dados de leitura e escrita de qual escola?</h1>
            <div class="searcheble">
                <input class="seacheble-camp" placeholder="Digite o nome do município" :value="search"
                    @input="search = $event.target.value">
                <a class="send-searche">
                    <svg width="13" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path
                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                    </svg>
                    Pesquisar
                </a>
            </div>

            <ButtonComponent TextValue="Selecionar Todas" @click="selectAllSchools" />

            <ButtonComponent v-for="({ name }, index) in filteredSchools" :key="index" :TextValue="name"
                @click="() => navigateToSchool(selectedCity, name)" />
        </div>
    </div>
</template>

<style scoped>
.searcheble {
    display: flex;
    align-items: center;
    gap: 5%;
    width: 100%;

    & .seacheble-camp {
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

.school-register {
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
        & .seacheble-camp {
            width: 60% !important;
        }

        & .send-searche {
            width: 35%;
        }
    }
}

@media (max-width: 500px) {
    .school-register {
        & .register-content {
            padding: 0 3rem;
            gap: 3rem;

            & h1 {
                text-align: center;
            }
        }
    }

    .searcheble {
        display: flex;
        flex-direction: column !important;
        margin: 0;

        & .seacheble-camp {
            width: 100% !important;
        }

        & .send-searche {
            width: 100%;
        }
    }
}
</style>