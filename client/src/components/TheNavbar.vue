<template>
    <div class="nav">
        <h2 class="nav__top" @click="urls">
            Url Shortener
        </h2>
        <h2 class="nav__mid"></h2>
        <button class="nav__bot" @click="logout">Logout</button>
    </div>
</template>

<script lang="ts">

export default {
    name: 'TheNavbar',
    methods: {
        logout(){
            localStorage.removeItem('token');
            this.$router.push('/login');
        },
        urls(){
            this.$router.push('/urls');
        },
    }
}
</script>
<style scoped>
.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1em 2em;
  background-color: #243540;
  margin-bottom: 2em;
  color: #fff;
}


.nav__bot {
  padding: 0.5em 1em;
  background-color: #dcdcdc;
  color: #243540;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.nav__bot:hover {
  background-color: #f3f3f3;
}

</style>