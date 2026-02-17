<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Materia {
    id: number
    codigo: string
    nombre: string
}

interface Edificio {
    id: number
    codigo: string
    nombre: string
}

interface Horario {
    id: number
    codigo: string
    dia_semana: string
    hora_inicio: string
    duracion: string
    materia_id: number
    edificio_id: number
    materia: Materia
    edificio: Edificio
}

const props = defineProps<{
    horarios: Horario[]
    materias: Materia[]
    edificios: Edificio[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Horarios', href: '/horarios' }
]

// FORMULARIO CREAR
const form = useForm({
    codigo: '',
    dia_semana: '',
    hora_inicio: '',
    duracion: '',
    materia_id: 0,
    edificio_id: 0
})

// FORMULARIO EDITAR
const editForm = useForm({
    id: 0,
    codigo: '',
    dia_semana: '',
    hora_inicio: '',
    duracion: '',
    materia_id: 0,
    edificio_id: 0
})

const showEditModal = ref(false)

const submit = () => {
    form.post('/horarios', {
        onSuccess: () => form.reset()
    })
}

const startEdit = (horario: Horario) => {
    editForm.id = horario.id
    editForm.codigo = horario.codigo
    editForm.dia_semana = horario.dia_semana
    editForm.hora_inicio = horario.hora_inicio
    editForm.duracion = horario.duracion
    editForm.materia_id = horario.materia_id
    editForm.edificio_id = horario.edificio_id
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/horarios/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

const eliminar = (id: number) => {
    if (confirm('¿Eliminar horario?')) {
        form.delete(`/horarios/${id}`)
    }
}

// BÚSQUEDA
const search = useForm({
    search: props.filters?.search ?? ''
})

const buscar = () => {
    search.get('/horarios/buscar', {
        preserveState: true,
        replace: true
    })
}

</script>

<template>
    <Head title="Horarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">

            <!-- FORMULARIO CREAR -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Nuevo Horario</h2>
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <input v-model="form.codigo" placeholder="Código (HOR-001)" class="border p-2 rounded" />
                    <input v-model="form.dia_semana" placeholder="Día de la semana" class="border p-2 rounded" />
                    <input type="time" v-model="form.hora_inicio" class="border p-2 rounded" />
                    <input type="time" v-model="form.duracion" class="border p-2 rounded" />
                    <select v-model.number="form.materia_id" class="border p-2 rounded">
                        <option value="0">Seleccionar Materia</option>
                        <option v-for="mat in props.materias" :key="mat.id" :value="mat.id">
                            {{ mat.codigo }} - {{ mat.nombre }}
                        </option>
                    </select>
                    <select v-model.number="form.edificio_id" class="border p-2 rounded">
                        <option value="0">Seleccionar Edificio</option>
                        <option v-for="edi in props.edificios" :key="edi.id" :value="edi.id">
                            {{ edi.codigo }} - {{ edi.nombre }}
                        </option>
                    </select>
                    <button type="submit" class="col-span-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600" :disabled="form.processing">
                        Guardar
                    </button>
                </form>

                <div v-if="form.errors" class="text-red-500 mt-4">
                    <div v-for="(error, key) in form.errors" :key="key">{{ error }}</div>
                </div>
            </div>

            <!-- BÚSQUEDA -->
            <div class="mb-4">
                <input
                    v-model="search.search"
                    @input="buscar"
                    placeholder="Buscar horario..."
                    class="border p-2 rounded w-full"
                />
            </div>

            <!-- LISTADO -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Lista de Horarios</h2>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Código</th>
                            <th class="p-2 border">Día</th>
                            <th class="p-2 border">Hora Inicio</th>
                            <th class="p-2 border">Duración</th>
                            <th class="p-2 border">Materia</th>
                            <th class="p-2 border">Edificio</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="hor in props.horarios" :key="hor.id">
                            <td class="border p-2">{{ hor.codigo }}</td>
                            <td class="border p-2">{{ hor.dia_semana }}</td>
                            <td class="border p-2">{{ hor.hora_inicio }}</td>
                            <td class="border p-2">{{ hor.duracion }}</td>
                            <td class="border p-2">{{ hor.materia.nombre }}</td>
                            <td class="border p-2">{{ hor.edificio.nombre }}</td>
                            <td class="border p-2 space-x-2">
                                <button @click="startEdit(hor)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </button>
                                <button @click="eliminar(hor.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- MODAL EDITAR -->
            <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow w-1/2">
                    <h2 class="text-lg font-bold mb-4">Editar Horario</h2>
                    <form @submit.prevent="update" class="grid grid-cols-2 gap-4">
                        <input v-model="editForm.codigo" placeholder="Código" class="border p-2 rounded" />
                        <input v-model="editForm.dia_semana" placeholder="Día de la semana" class="border p-2 rounded" />
                        <input type="time" v-model="editForm.hora_inicio" class="border p-2 rounded" />
                        <input type="time" v-model="editForm.duracion" class="border p-2 rounded" />
                        <select v-model.number="editForm.materia_id" class="border p-2 rounded col-span-2">
                            <option value="0">Seleccionar Materia</option>
                            <option v-for="mat in props.materias" :key="mat.id" :value="mat.id">
                                {{ mat.codigo }} - {{ mat.nombre }}
                            </option>
                        </select>
                        <select v-model.number="editForm.edificio_id" class="border p-2 rounded col-span-2">
                            <option value="0">Seleccionar Edificio</option>
                            <option v-for="edi in props.edificios" :key="edi.id" :value="edi.id">
                                {{ edi.codigo }} - {{ edi.nombre }}
                            </option>
                        </select>
                        <button type="submit" class="col-span-2 bg-green-500 text-white p-2 rounded hover:bg-green-600" :disabled="editForm.processing">
                            Actualizar
                        </button>
                        <button type="button" @click="showEditModal = false" class="col-span-2 mt-2 bg-gray-500 text-white p-2 rounded hover:bg-gray-600">
                            Cancelar
                        </button>
                    </form>

                    <div v-if="editForm.errors" class="text-red-500 mt-4">
                        <div v-for="(error, key) in editForm.errors" :key="key">{{ error }}</div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
