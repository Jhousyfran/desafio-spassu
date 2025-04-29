<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <router-link class="navbar-brand fw-bold" to="/">Library</router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><router-link class="nav-link" to="/authors">Autores</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/topics">Assuntos</router-link></li>
          <li class="nav-item"><router-link class="nav-link" to="/books">Livros</router-link></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <router-view />
  </div>
</template>
