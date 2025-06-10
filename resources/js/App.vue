<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-gray-800 text-white w-64 fixed h-full">
      <div class="p-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Roboflex" class="w-24 mx-auto mb-8" />
        <div class="space-y-2">
          <a v-for="menu in menus" :key="menu.id" 
             :href="menu.link" 
             target="_blank" 
             class="block px-4 py-2 hover:bg-gray-700 transition-colors">
            {{ menu.name }}
          </a>
        </div>
      </div>
    </nav>

    <main class="ml-64 p-8">
      <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold text-gray-800">Gerenciador de Menus</h1>
          <button @click="openModal" 
                  class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
            Adicionar Menu
          </button>
        </div>

        <div class="bg-white rounded-lg shadow">
          <div v-if="menus.length === 0" class="p-8 text-center text-gray-500">
            Nenhum menu cadastrado.
          </div>
          <div v-else class="divide-y">
            <div v-for="menu in menus" :key="menu.id" class="p-6 flex justify-between items-center">
              <div>
                <h3 class="font-semibold text-gray-800">{{ menu.name }}</h3>
                <a :href="menu.link" target="_blank" class="text-blue-500 hover:underline">
                  {{ menu.link }}
                </a>
              </div>
              <div class="space-x-2">
                <button @click="editMenu(menu)" 
                        class="text-yellow-500 hover:text-yellow-600">
                  ✏️
                </button>
                <button @click="deleteMenu(menu.id)" 
                        class="text-red-500 hover:text-red-600">
                  🗑️
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal -->
    <div v-if="showModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
         @click.self="closeModal">
      <div class="bg-white rounded-lg p-6 w-96">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-gray-800">
            {{ isEditing ? 'Editar Menu' : 'Novo Menu' }}
          </h2>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
            ✕
          </button>
        </div>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Nome
            </label>
            <input v-model="form.name" 
                   type="text" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Link
            </label>
            <input v-model="form.link" 
                   type="url" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
          <button @click="closeModal" 
                  class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            Cancelar
          </button>
          <button @click="saveMenu" 
                  class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            {{ isEditing ? 'Atualizar' : 'Salvar' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  data() {
    return {
      menus: [],
      showModal: false,
      isEditing: false,
      form: {
        id: null,
        name: '',
        link: ''
      }
    }
  },
  methods: {
    async loadMenus() {
      try {
        const response = await axios.get('/api/menus');
        console.log('Resposta da API:', response.data);
        this.menus = response.data;
      } catch (error) {
        console.error('Erro ao carregar menus:', error.response?.data || error.message);
        alert('Erro ao carregar menus: ' + (error.response?.data?.error || error.message));
      }
    },
    openModal() {
      this.isEditing = false;
      this.form = { id: null, name: '', link: '' };
      this.showModal = true;
    },
    editMenu(menu) {
      this.isEditing = true;
      this.form = { ...menu };
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.form = { id: null, name: '', link: '' };
    },
    async saveMenu() {
      if (!this.form.name.trim() || !this.form.link.trim()) {
        alert('Preencha todos os campos');
        return;
      }

      try {
        if (this.isEditing) {
          const response = await axios.put(`/api/menus/${this.form.id}`, this.form);
          console.log('Menu atualizado:', response.data);
        } else {
          const response = await axios.post('/api/menus', this.form);
          console.log('Menu criado:', response.data);
        }
        await this.loadMenus();
        this.closeModal();
      } catch (error) {
        console.error('Erro ao salvar menu:', error.response?.data || error.message);
        alert('Erro ao salvar menu: ' + (error.response?.data?.error || error.message));
      }
    },
    async deleteMenu(id) {
      if (confirm('Tem certeza que deseja excluir este menu?')) {
        try {
          await axios.delete(`/api/menus/${id}`);
          console.log('Menu excluído com sucesso');
          await this.loadMenus();
        } catch (error) {
          console.error('Erro ao excluir menu:', error.response?.data || error.message);
          alert('Erro ao excluir menu: ' + (error.response?.data?.error || error.message));
        }
      }
    }
  },
  mounted() {
    this.loadMenus();
  }
}
</script> 