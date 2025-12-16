<script setup>
const config = useRuntimeConfig()
const page = ref(1)
const showModal = ref(false)

const { data, pending, error, refresh } = await useFetch(
  () => `${config.public.apiBase}/holidays?page=${page.value}`
)

function goToPage(p) {
  page.value = p
  refresh()
}

function openCreateModal() {
  isEdit.value = false
  errorMessage.value = ''
  Object.assign(form, {
    id: null,
    title: '',
    date: '',
    type: '',
    inalienable: false,
  })
  showModal.value = true
}

function openEditModal(holiday) {
  isEdit.value = true
  errorMessage.value = ''
  Object.assign(form, holiday)
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  errorMessage.value = ''
}

const form = reactive({
  id: null,
  title: '',
  date: '',
  type: '',
  inalienable: false,
})

async function saveHoliday() {
  // Validación frontend
  if (!form.title || !form.date) {
    errorMessage.value = 'Nombre y fecha son obligatorios'
    return
  }

  loading.value = true
  errorMessage.value = ''

  try {
    if (isEdit.value) {
      // EDITAR
      await $fetch(`${config.public.apiBase}/holidays/${form.id}`, {
        method: 'PUT',
        body: form,
      })
    } else {
      // CREAR
      await $fetch(`${config.public.apiBase}/holidays`, {
        method: 'POST',
        body: form,
      })
    }

    showModal.value = false
    refresh()
  } catch (err) {
  const msg = err?.data?.message || ''

  if (msg.includes('date has already been taken')) {
    errorMessage.value = 'Ya existe un feriado con esa fecha'
  } else {
    errorMessage.value = 'Error al guardar el feriado'
  }
} finally {
    loading.value = false
  }
}

async function deleteHoliday(id) {
  if (!confirm('¿Seguro que deseas eliminar este feriado?')) return

  loading.value = true
  errorMessage.value = ''

  try {
    await $fetch(`${config.public.apiBase}/holidays/${id}`, {
      method: 'DELETE',
    })

    refresh()
  } catch (err) {
    errorMessage.value =
      err?.data?.message || 'Error al eliminar el feriado'
  } finally {
    loading.value = false
  }
}

const loading = ref(false)
const errorMessage = ref('')
const isEdit = ref(false)
</script>

<template>
  <section class="section">
    <div class="container">
      <h1 class="title">Feriados</h1>

        <div v-if="pending" class="has-text-centered">
        <button class="button is-loading is-white">
            Cargando feriados...
        </button>
        </div>
        <div v-if="error">Error al cargar feriados</div>
        <div v-if="errorMessage" class="notification is-danger mb-4">
        {{ errorMessage }}
        </div>
        <div class="mb-4 is-flex is-justify-content-flex-end">
        <button class="button is-primary" @click="openCreateModal">
            + Crear feriado
        </button>
        </div>
        <table v-if="data" class="table is-striped is-fullwidth">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Título</th>
                <th>Tipo</th>
                <th>Irrenunciable</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="f in data.data" :key="f.id">
                <td>{{ f.date }}</td>
                <td>{{ f.title }}</td>
                <td>{{ f.type }}</td>
                <td>
                <span v-if="f.inalienable">Sí</span>
                <span v-else>No</span>
                </td>
                <td>
                    <div class="buttons are-small">
                    <button
                    class="button is-info is-light"
                    @click="openEditModal(f)"
                    >
                    Editar
                    </button>
                    <button
                    class="button is-danger is-light"
                    :class="{ 'is-loading': loading }"
                    @click="deleteHoliday(f.id)"
                    >
                    Eliminar
                    </button>
                </div>
                </td>
            </tr>
            </tbody>
        </table>
        <nav
        class="pagination is-centered"
        role="navigation"
        aria-label="pagination"
        v-if="data"
        >
        <a
            class="pagination-previous"
            :disabled="!data.prev_page_url"
            @click="goToPage(page - 1)"
        >
            Anterior
        </a>

        <a
            class="pagination-next"
            :disabled="!data.next_page_url"
            @click="goToPage(page + 1)"
        >
            Siguiente
        </a>

        <ul class="pagination-list">
            <li v-for="p in data.last_page" :key="p">
            <a
                class="pagination-link"
                :class="{ 'is-current': p === data.current_page }"
                @click="goToPage(p)"
            >
                {{ p }}
            </a>
            </li>
        </ul>
        </nav>
    </div>
  </section>

  <div class="modal" :class="{ 'is-active': showModal }">
    <div class="modal-background" @click="closeModal"></div>

    <div class="modal-card">
        <header class="modal-card-head">
        <p class="modal-card-title">Crear feriado</p>
        <button class="delete" aria-label="close" @click="closeModal"></button>
        </header>

        <section class="modal-card-body">
        <!-- FORMULARIO -->
        <div v-if="errorMessage" class="notification is-danger">
            {{ errorMessage }}
        </div>

        <div class="field">
            <label class="label">Nombre</label>
            <div class="control">
            <input class="input" type="text" v-model="form.title" maxlength="50">
            </div>
        </div>

        <div class="field">
            <label class="label">Fecha</label>
            <div class="control">
            <input class="input" type="date" v-model="form.date">
            </div>
        </div>

        <div class="field">
            <label class="label">Tipo</label>
            <div class="control">
            <input class="input" type="text" v-model="form.type" maxlength="50">
            </div>
        </div>

        <div class="field">
            <label class="checkbox">
            <input type="checkbox" v-model="form.inalienable">
            Irrenunciable
            </label>
        </div>
        </section>

        <footer class="modal-card-foot">
            <button
            class="button is-success"
            :class="{ 'is-loading': loading }"
            @click="saveHoliday"
            >
            Guardar
            </button>
            <button class="button" @click="closeModal">
                Cancelar
            </button>
        </footer>
    </div>
</div>
</template>