<script setup>
import menuMobile from '/resources/js/components/mobileMenu.vue'
</script>

<template>
    <div class="welcome-component">
    <div class="mobile-menu">
        <menuMobile></menuMobile>
    </div>

        <div class="welcome-title">
            <p>Que bom ter você aqui, admin</p>
            <img src="/public/assets/userIcon.png">
        </div>
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

        & .welcome-title{
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        & .mobile-menu{
            display: none;
        }
    }


@media (max-width: 900px) {
    .welcome-component{
        width: 100vw;
        border-radius: 0;
        display: flex;
        justify-content: space-between;
        border-top: 0 ;
        border-left: 0 ;
        border-right: 0 ;
        border-bottom: 2px solid var(--secondary-color);

        & .mobile-menu{
            display: flex;
        }
    }
}
</style>