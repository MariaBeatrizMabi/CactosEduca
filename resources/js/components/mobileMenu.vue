<script>
export default {
    data() {
        return {
            menuOpen: false
        };
    },
    methods: {
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        }
    }
};
</script>

<template>
    <header class="header-nav">
        <nav>
            <button id="btn-mobile" class="btn-mobile" @click="toggleMenu">Menu</button>
            <ul id="menu" class="menu-mobile-itens" v-if="menuOpen">
                <li class="nav-link">
                    <a href="/dashboard">
                     Inicio
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/Filters">
                       Dashboard
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/Management">
                       Escolas
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/ContactUs">
                        Contate-nos
                    </a>
                </li>
                <li class="nav-link">
                    <a href="/">
                        Sair
                    </a>
                </li>
            </ul>
        </nav>
    </header>
</template>

<style scoped>
.menu-mobile-itens > li {
    list-style: none;
}

.btn-mobile {
    display: block;
}

ul {
    display: flex;
}

#menu {
    position: absolute;
    width: 100%;
    top: 60px;
    right: 0;
    background-color: var(--grap-color);
    display: block;
    height: calc(100vh - 60px);
    z-index: 1000;

    & li > a {
        padding: 2rem;
        border-bottom: 2px solid var(--secondary-color);
    }
    
}
</style>
