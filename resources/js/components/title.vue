<script setup>
const props = defineProps({
    title: {
        type: String
    },
})

</script>

<template>
    <div class="title-component">
        <h1>{{ title }}</h1>
    </div>
</template>

<style>
.title-component {
    background-color: var(--primary-color);
    color: white;

    width: 84%;
    height: 3rem;
    justify-content: center;
    display: flex;
    align-items: center;
    
    font-size: 11px;
    font-weight: 400;

    border-radius: 0.8rem;
    text-align: center;
}
</style>