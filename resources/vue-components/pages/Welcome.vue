<template>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-4 text-center">{{ __('common.welcome') }} <span class="text-danger">Laravel</span> + <span class="text-warning">Vite</span> + <span class="text-success">Vue3</span></h1>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <a class="btn btn-outline-primary me-3" href="?lang=en">English</a>
                    <a class="btn btn-outline-success" href="?lang=it">Italian</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    inject: ['__'],
    name: "Welcome",
}
</script>

<style scoped>

</style>
