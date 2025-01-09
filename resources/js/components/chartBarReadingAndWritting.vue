<script setup>
import GrapichBarBimReading from '/resources/js/components/grapichBarBimReading.vue';
import GrapichBarBimWriting from "./grapichBarBimWriting.vue";

const props = defineProps({
    titleGrapichCard: {
        type: String
    }
});
</script>

<template>
    <div class="cards">
        <div class="card-grapich">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>Análise dividida por sondagem - Leitura</h1>
                </div>
            </div>
            <div class="grapich">
                <GrapichBarBimReading></GrapichBarBimReading>
            </div>
        </div>

        <div class="card-grapich">
            <div class="card-grapich-content">
                <div class="card-title">
                    <h1>Análise dividida por sondagem - Escrita</h1>
                </div>
            </div>
            <div class="grapich">
                <GrapichBarBimWriting></GrapichBarBimWriting>
            </div>
        </div>
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
        width: 40%;

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
            justify-content: center;
            flex-direction: column;
            padding: 1rem;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }
    }

    & .card-grapich {
        margin: 3rem 0;
        width: 40%;

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
            flex-direction: column;
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
