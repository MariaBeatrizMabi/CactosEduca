<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Grapich from '/resources/js/components/grapich.vue';
import PieChart from '/resources/js/components/pieChart.vue';

const userType = ref('');

const props = defineProps({
    titleGrapichCard: {
        type: String
    }
});

const getUserType = () => {
    axios.get('/loginUser').then(response => {
        userType.value = response.data.type;
    }).catch(error => {
        console.log("ERROR", error);
    });
}

onMounted(() => {
    getUserType();
});
    
</script>

<template>
    <div class="cards">
        <div class="card-grapich" v-if="userType === 'admin'">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <Grapich></Grapich>
            </div>
        </div>
        
        <div class="card-grapich" v-if="userType === 'admin'">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <PieChart></PieChart>
            </div>
        </div>

        <div class="card-grapich" v-if="userType === 'school'">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <Grapich></Grapich>
            </div>
        </div>

        <div class="card-grapich" v-if="userType === 'school'">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <PieChart></PieChart>
            </div>
        </div>

        <div class="card-grapich" v-if="userType === 'teacher'">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <Grapich></Grapich>
            </div>
        </div>

        <div class="card-grapich" v-if="userType === 'teacher'">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <PieChart></PieChart>
            </div>
        </div>

        <!-- <div class="card-grapich" v-else>
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>{{ titleGrapichCard }}</h1>
                </div>
            </div>
            <div class="grapich">
                <h1 class="user-not-Found">Tipo de usuário não encontrado</h1>
            </div>
        </div> -->
    </div>  
</template>

<style scoped>
.cards{
    display: flex;
    width: 100%;
    justify-content: center;
    gap: 3rem;

    & .card-grapich:first-child {
        margin: 3rem 0;
        width: 52%;

        display: flex;
        flex-direction: column;

        border-radius: 1rem;
        border: 3px solid var(--secondary-color);

        background-color: var(--secondary-color);

        & .card-grapich-content {
            & .card-title {
                & h1 {
                    margin: 0.5rem;
                    text-align: center;

                    color: white;
                    
                    font-weight: 400;
                    font-size: 20px;
                }
            }
        }
        & .grapich {
            width: 100%;
            display: flex;
            padding: 1rem;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }
    }

    & .card-grapich {
        margin: 3rem 0;
        width: 30%;

        display: flex;
        flex-direction: column;

        border-radius: 1rem;
        border: 3px solid var(--secondary-color);

        background-color: var(--secondary-color);

        & .card-grapich-content {
            & .card-title {
                & h1 {
                    margin: 0.5rem;
                    text-align: center;

                    color: white;
                    
                    font-weight: 400;
                    font-size: 20px;
                }
            }
        }
        & .grapich {
            width: 100%;
            height: 100%;
            display: flex;
            padding: 1rem;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }
    }

        @media (max-width: 1300px) {
            & .card-grapich:first-child {
                width: 84%;
            }
            & .card-grapich {
            width: 84%;
        }

       
    }
}

</style>