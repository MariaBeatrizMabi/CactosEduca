<script setup>

</script>

<template>
    <div class="welcome-component">
        <p>Que bom ter você aqui, admin</p>
        <img src="/public/assets/userIcon.png">
    </div>
</template>

<style>
    .welcome-component {
        position: absolute;
        display: flex;
        align-items: center;
        gap: 1rem;
        top: 0;
        right: 0;
        padding: 0.8rem 3rem;
        background-color: var(--grap-color);
        border-bottom: 2px solid var(--secondary-color);
        border-left: 2px solid var(--secondary-color);
        border-radius: 0 0 0 3rem;
        & p {
            color: var(--primary-color);
            font-size: 18px;
            font-weight: 500;
        }
    }
</style>