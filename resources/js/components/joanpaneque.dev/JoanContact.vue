<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const submitForm = () => {
  form.post(route('api.contact.store'), {
    preserveScroll: true,
    onSuccess: () => {
      // Form will be reset automatically by Inertia on success
    },
    onError: (errors) => {
      // Errors will be handled automatically by Inertia
      console.log('Validation errors:', errors)
    }
  })
}
</script>

<template>
  <div class="pt-16 scroll-mt-20" id="contact">
    <h2 class="font-semibold text-4xl md:text-5xl text-balance text-black text-center mb-8">
      Contáctame
    </h2>
    
    <div class="bg-white p-8 md:p-16 rounded-3xl max-w-4xl mx-auto border border-gray-200">
      <!-- Success Message -->
      <div v-if="form.wasSuccessful" class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
        <div class="flex items-center gap-2">
          <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <p class="text-emerald-800 font-medium">¡Mensaje enviado correctamente!</p>
        </div>
        <p class="text-emerald-700 text-sm mt-1">Te responderé lo antes posible.</p>
      </div>
      
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Name and Email Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Nombre *
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
              :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors.name }"
              placeholder="Tu nombre completo"
            />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
          </div>
          
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email *
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
              :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors.email }"
              placeholder="tu@email.com"
            />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
          </div>
        </div>
        
        <!-- Subject Field -->
        <div>
          <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
            Asunto *
          </label>
          <input
            id="subject"
            v-model="form.subject"
            type="text"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
            :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors.subject }"
            placeholder="¿En qué puedo ayudarte?"
          />
          <p v-if="form.errors.subject" class="text-red-500 text-sm mt-1">{{ form.errors.subject }}</p>
        </div>
        
        <!-- Message Field -->
        <div>
          <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
            Mensaje *
          </label>
          <textarea
            id="message"
            v-model="form.message"
            rows="6"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
            :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors.message }"
            placeholder="Cuéntame sobre tu proyecto o consulta..."
          ></textarea>
          <p v-if="form.errors.message" class="text-red-500 text-sm mt-1">{{ form.errors.message }}</p>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-center pt-4">
          <button
            type="submit"
            :disabled="form.processing"
            class="bg-[#002626] text-white px-8 py-3 rounded-full font-medium hover:bg-[#003333] disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors duration-300 flex items-center gap-2 min-w-[160px] justify-center"
          >
            <span v-if="form.processing">
              <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Enviando...
            </span>
            <span v-else>
              Enviar mensaje
            </span>
          </button>
        </div>
      </form>
      
      <!-- Contact Info -->
      <div class="mt-12 pt-8 border-t border-gray-200">
        <div class="text-center">
          <p class="text-gray-600 mb-4">También puedes contactarme directamente:</p>
          <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a 
              href="mailto:joanpd0@gmail.com" 
              class="text-[#002626] font-bold underline hover:text-[#003333] transition-colors flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              joanpd0@gmail.com
            </a>
            <a 
              href="https://www.linkedin.com/in/joan-paneque-domingo-906340237/" 
              target="_blank" 
              class="text-[#002626] font-bold underline hover:text-[#003333] transition-colors flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
              LinkedIn
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template> 