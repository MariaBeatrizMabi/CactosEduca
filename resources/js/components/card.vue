<script setup>
import { ref } from 'vue';

const props = defineProps({
    imageCard: {
        type: String,
        required: true
    },
    valueCard: {
        type: Number,
        required: true
    },
    titleCard: {
        type: String,
        required: true
    }
})

const imageSrc = ref(`/assets/${props.imageCard}`);
</script>

<template>
    <div class="card">
        <div class="card-content">
            <div class="gif-spot">
                <img :src="imageSrc" class="gif" alt="">
            </div>
            <h1>{{ valueCard }}</h1>
            <p>{{ titleCard }}</p>
        </div>
    </div>
</template>

<style scoped>
    .card-content{
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;

        background-color: var(--grap-color);
        padding: 3rem 0;
        width: 18rem;
        max-width: 18rem;

        background-image: url('/public/assets/cardDetail.png');
        background-repeat: no-repeat;

        border: 2px solid var(--primary-color);
        border-radius: 2rem;

        & .gif-spot {
            background-color: white;
            border: 2px solid var(--primary-color);

            border-radius: 4rem;

            padding: 0.7rem;
            
            & .gif {
                border-radius: 2rem;
                width: 5rem;
            }
        }

        & h1 {
            font-size: 34px;
            color: var(--primary-color);
            margin: 0.4rem 0;
        }

        & p {
            color: var(--black-color);
            font-size: 16px;
        }
    }
</style>