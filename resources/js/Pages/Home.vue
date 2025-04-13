<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { onMounted, ref, watch, computed, onUnmounted } from "vue";
import { useI18n } from "vue-i18n";
import HeaderDropdown from "../Components/HeaderDropdown.vue";
import FeaturedProject from "../Components/FeaturedProject.vue";

// Configuración de i18n
const { t, locale, availableLocales } = useI18n();

// Contador de tiempo ahorrado para el proyecto Trueta
const savedSeconds = ref(0);
const operacionesRealizadas = ref(0);

// Contador de análisis de IA para el proyecto ObservaAI
const observaAIAnalysisCount = ref(0);

// Pestaña activa en la sección "Sobre mí"
const activeTab = ref('about'); // Valores: 'about', 'stack', 'interests'

// Galería de imágenes para el proyecto Trueta
const galleryImages = [
  { 
    type: 'image', 
    src: '/uploads/trueta/img1.jpg',
    gradient: 'from-blue-400/20 to-cyan-400/20',
    title: 'Presentación de la aplicación a los cirujanos'
  },
  { 
    type: 'image', 
    src: '/uploads/trueta/img2.png',
    gradient: 'from-blue-400/20 to-indigo-400/20',
    title: 'Parte del equipo de desarrollo del proyecto'
  },
  { 
    type: 'image', 
    src: '/uploads/trueta/img3.png',
    gradient: 'from-cyan-400/20 to-blue-400/20',
    title: 'Interfaz de la sección de cirugías de la aplicación'
  },
];

// Galería de imágenes para el proyecto ObservaAI
const observaAIGalleryImages: Array<{
  type?: string;
  src: string;
  gradient?: string;
  title: string;
}> = [];

const savedTimeFormatted = computed(() => {
  const totalSeconds = savedSeconds.value;
  const days = Math.floor(totalSeconds / (24 * 60 * 60));
  const hours = Math.floor((totalSeconds % (24 * 60 * 60)) / (60 * 60));
  const minutes = Math.floor((totalSeconds % (60 * 60)) / 60);
  const seconds = (totalSeconds % 60).toFixed(1); // Mostrar con 1 decimal
  
  return {
    days,
    hours,
    minutes,
    seconds
  };
});

// Intervalo para actualizar el tiempo ahorrado
let savedTimeInterval: number | null = null;
let aiAnalysisInterval: number | null = null;

// Función para calcular el tiempo ahorrado desde el 1 de junio de 2024
const calculateSavedTime = () => {
  const startDate = new Date("2024-07-24T00:00:00");
  const now = new Date();
  const elapsedMilliseconds = now.getTime() - startDate.getTime();
  const elapsedSeconds = elapsedMilliseconds / 1000;
  
  // Cada segundo real se ahorran 0.1925 segundos
  savedSeconds.value = elapsedSeconds * 0.1925;
  
  // Cálculo de operaciones realizadas (15.000 operaciones por año)
  // 15000 ops ÷ 365 días ÷ 24 horas ÷ 60 minutos ÷ 60 segundos = 0.000475687 ops/segundo
  const operacionesPorSegundo = 15000 / (365 * 24 * 60 * 60);
  operacionesRealizadas.value = elapsedSeconds * operacionesPorSegundo;
};

// Función para calcular el número de análisis de IA ejecutados
const calculateAIAnalysis = () => {
  const startDate = new Date("2025-04-05T00:00:00");
  const now = new Date();
  
  // Verificar que la fecha de inicio sea en el futuro
  if (now < startDate) {
    observaAIAnalysisCount.value = 0;
    return;
  }
  
  const elapsedMilliseconds = now.getTime() - startDate.getTime();
  const elapsedMinutes = elapsedMilliseconds / (1000 * 60);
  
  // 0.3 análisis por minuto
  observaAIAnalysisCount.value = Math.floor(elapsedMinutes * 0.3);
};

// Control para mostrar/ocultar detalles del proyecto Trueta
const showTruetaDetails = ref(false);

// Variables para el modal de imágenes
const showImageModal = ref(false);
const selectedImageIndex = ref(0);

// Función para abrir el modal con la imagen seleccionada
const openImageModal = (index: number) => {
  selectedImageIndex.value = index;
  showImageModal.value = true;
  
  // Prevenir el scroll del body mientras el modal está abierto
  document.body.style.overflow = 'hidden';
  
  // Aplicar la animación pulse al elemento que se ha clicado
  // Necesitamos esperar un momento para asegurar que el DOM está actualizado
  setTimeout(() => {
    const clickedImage = document.querySelector(`.gallery-image-${index}`);
    if (clickedImage) {
      clickedImage.classList.add('pulse-on-click');
      
      // Eliminar la clase después de la animación para que se pueda reproducir de nuevo
      setTimeout(() => {
        clickedImage.classList.remove('pulse-on-click');
      }, 300); // Duración de la animación
    }
  }, 0);
};

// Función para cerrar el modal
const closeImageModal = () => {
  showImageModal.value = false;
  // Restaurar el scroll del body
  document.body.style.overflow = '';
};

// Listeners para cerrar el modal con Escape
onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});

const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && showImageModal.value) {
    closeImageModal();
  }
};

// Preparar opciones de idioma para el dropdown
const languageOptions = computed(() => {
  return availableLocales.map((lang) => ({
    value: lang,
    label: t(`language.${lang}`),
  }));
});

// Función para cambiar el idioma
const changeLanguage = (lang: string) => {
  locale.value = lang;
  localStorage.setItem("language", lang);
};

// Watch para reiniciar la terminal cuando cambia el idioma
watch(locale, () => {
  // Detener la animación actual si existe
  if (typingTimeout) {
    clearTimeout(typingTimeout);
    typingTimeout = null;
  }

  // Reiniciar la terminal
  terminalLines.value = [];
  commandIndex.value = 0;
  charIndex.value = 0;
  isJsonSection.value = false;
  userScrolled.value = false;
  isAtBottom.value = true;

  // Iniciar la animación de nuevo
  startTerminalAnimation();
});

// Dark mode toggle implementation
const isDarkMode = ref(true);

// Estado para controlar la apariencia del header al hacer scroll
const isScrolled = ref(false);

// Función para detectar el scroll y actualizar el estado
const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

// Check localStorage and system preference on mount
onMounted(() => {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    isDarkMode.value = true;
  } else if (savedTheme === "light") {
    isDarkMode.value = false;
  } else {
    // Si no hay preferencia guardada, usar modo oscuro por defecto
    isDarkMode.value = true;
  }
  updateTheme();

  // Añadir event listener para el movimiento del mouse en toda la ventana
  window.addEventListener("mousemove", handleGlobalMouseMove);

  // Añadir event listener para el scroll
  window.addEventListener("scroll", handleScroll);

  // Iniciar animación de escritura para la terminal
  startTerminalAnimation();

  // Iniciar las partículas
  initParticles();

  // Añadir event listener para el scroll en la terminal
  if (terminalRef.value) {
    terminalRef.value.addEventListener("scroll", handleTerminalScroll);
  }

  // Iniciar el contador de tiempo ahorrado
  calculateSavedTime();
  savedTimeInterval = setInterval(() => {
    calculateSavedTime();
  }, 100);
  
  // Iniciar el contador de análisis de IA
  calculateAIAnalysis();
  aiAnalysisInterval = setInterval(() => {
    calculateAIAnalysis();
  }, 1000);

  // Limpieza
  return () => {
    window.removeEventListener("mousemove", handleGlobalMouseMove);
    window.removeEventListener("scroll", handleScroll);
    if (particleInterval) clearInterval(particleInterval);
    if (terminalRef.value) {
      terminalRef.value.removeEventListener("scroll", handleTerminalScroll);
    }
    if (savedTimeInterval) {
      clearInterval(savedTimeInterval);
    }
    if (aiAnalysisInterval) {
      clearInterval(aiAnalysisInterval);
    }
  };
});

// Update theme when isDarkMode changes
watch(isDarkMode, () => {
  updateTheme();
  localStorage.setItem("theme", isDarkMode.value ? "dark" : "light");
});

// Apply theme to document
const updateTheme = () => {
  if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
  } else {
    document.documentElement.classList.remove("dark");
  }
};

// Toggle dark mode
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
};

// Variables para el efecto 3D
const tiltX = ref(0);
const tiltY = ref(0);
const imageContainerRef = ref<HTMLElement | null>(null);

// Función para manejar el movimiento del mouse en cualquier parte de la pantalla
const handleGlobalMouseMove = (event: MouseEvent) => {
  if (!imageContainerRef.value) return;

  // Obtener tamaño de la ventana
  const windowWidth = window.innerWidth;
  const windowHeight = window.innerHeight;

  // Calcular la posición relativa del mouse en la ventana (0-1)
  const x = event.clientX / windowWidth;
  const y = event.clientY / windowHeight;

  // Convertir a un rango de -10 a 10 grados para el efecto tilt
  tiltX.value = (y * 2 - 1) * -8;
  tiltY.value = (x * 2 - 1) * 8;
};

// Variables para la terminal interactiva
const terminalText = ref("");
const terminalLines = ref<string[]>([]);
const terminalCursor = ref(true);
const typingSpeed = 50; // ms por caracter
let typingTimeout: number | null = null;
const terminalRef = ref<HTMLElement | null>(null);
const userScrolled = ref(false);
const isAtBottom = ref(true);

// Función para comprobar si el scroll está en la parte inferior
const checkIfAtBottom = () => {
  if (!terminalRef.value) return true;

  const { scrollTop, scrollHeight, clientHeight } = terminalRef.value;
  // Consideramos que está en el fondo si está a menos de 30px del final
  const atBottom = Math.abs(scrollHeight - scrollTop - clientHeight) < 30;
  isAtBottom.value = atBottom;
  return atBottom;
};

// Función para mantener el scroll de la terminal siempre abajo
const scrollTerminalToBottom = () => {
  if (!terminalRef.value) return;

  // Solo hacemos scroll automático si el usuario está mirando el fondo
  // o no ha interactuado aún con el scroll manual
  if (isAtBottom.value || !userScrolled.value) {
    setTimeout(() => {
      if (terminalRef.value) {
        // Usamos behavior:'auto' para escritura normal para que no sea distractivo
        // Solo usamos 'smooth' cuando el usuario ha hecho scroll manual y luego vuelve al fondo
        const scrollBehavior = userScrolled.value ? "smooth" : "auto";
        terminalRef.value.scrollTo({
          top: terminalRef.value.scrollHeight,
          behavior: scrollBehavior,
        });
      }
    }, 10);
  }
};

// Event listener para el scroll manual del usuario
const handleTerminalScroll = () => {
  userScrolled.value = true;
  checkIfAtBottom();
};

// Función para aplicar colores al JSON
const formatJsonSyntax = (text: string): string => {
  // Si la línea está vacía, la devolvemos tal cual
  if (!text || text.trim() === "") return text;

  try {
    let coloredText = text;

    // Colorizar las claves y valores JSON de manera más simple y robusta

    // Verificar si la línea contiene una clave de JSON
    if (text.includes('"') && text.includes(":")) {
      // Es una línea con clave JSON
      coloredText = text.replace(/"([^"]+)"/g, '<span class="text-blue-400">"$1"</span>');
    }
    // Si es valor de array (indentado y con comillas)
    else if (text.trim().startsWith('"') && (text.includes(",") || text.includes('"'))) {
      coloredText = text.replace(
        /"([^"]+)"/g,
        '<span class="text-emerald-400">"$1"</span>'
      );
    }

    // Colorizar llaves, corchetes, etc.
    coloredText = coloredText.replace(
      /([{}\[\],])/g,
      '<span class="text-gray-400">$1</span>'
    );

    return coloredText;
  } catch (e) {
    // Si hay algún error, devolvemos el texto original
    return text;
  }
};

// Comando que se escribirá automáticamente
const commands = computed(() => [
  "user@joan:~$ whoami",
  t("terminal.response1"),
  "user@joan:~$ cat skills.json",
  // El JSON se separa en líneas individuales para simular mejor el comportamiento de una terminal real
  "{",
  '  "frontend": [',
  `    "Vue.js",`,
  `    "React",`,
  `    "Lit Element",`,
  `    "Astro",`,
  `    "Angular",`,
  `    "TypeScript",`,
  `    "Tailwind CSS"`,
  "  ],",
  '  "backend": [',
  `    "Laravel",`,
  `    "Node.js",`,
  `    "Express",`,
  `    "PHP",`,
  `    "Java",`,
  `    "C/C++"`,
  "  ],",
  '  "databases": [',
  `    "MySQL",`,
  `    "MariaDB",`,
  `    "SQLite"`,
  "  ],",
  '  "devops": [',
  `    "Docker",`,
  `    "Git",`,
  `    "CI/CD",`,
  `    "Linux",`,
  `    "GitHub Actions"`,
  "  ],",
  `  "description": "${t("terminal.skills.description")}"`,
  "}",
  "user@joan:~$ ./experience.sh",
  t("terminal.experience1"),
  t("terminal.experience2"),
  t("terminal.experience3"),
  "user@joan:~$ echo $PASSION",
  t("terminal.passion"),
]);

const commandIndex = ref(0);
const charIndex = ref(0);
const isJsonSection = ref(false);

// Función para animar la escritura en la terminal
const startTerminalAnimation = () => {
  const typeNextChar = () => {
    if (commandIndex.value >= commands.value.length) {
      // Reiniciar la animación cuando se completa
      setTimeout(() => {
        terminalLines.value = [];
        commandIndex.value = 0;
        charIndex.value = 0;
        isJsonSection.value = false;
        userScrolled.value = false; // Reiniciar el estado de scroll manual
        isAtBottom.value = true; // Asumimos que al reiniciar está en el fondo
        startTerminalAnimation();
      }, 5000);
      return;
    }

    const currentCommand = commands.value[commandIndex.value];

    // Detectar si estamos en la sección del JSON
    if (currentCommand === "{") {
      isJsonSection.value = true;
    } else if (currentCommand === "user@joan:~$ experience-sh") {
      // Usar el comando hardcodeado directamente
      isJsonSection.value = false;
    }

    if (charIndex.value === 0) {
      terminalLines.value.push("");
      scrollTerminalToBottom(); // Scroll al agregar una nueva línea
    }

    if (charIndex.value < currentCommand.length) {
      const lastIndex = terminalLines.value.length - 1;
      terminalLines.value[lastIndex] = currentCommand.substring(0, charIndex.value + 1);

      // Solo hacemos scroll automático cada 10 caracteres para no interrumpir demasiado al usuario
      if (charIndex.value % 10 === 0) {
        scrollTerminalToBottom();
      }

      charIndex.value++;

      // Si estamos en el JSON, vamos más rápido
      const currentSpeed = isJsonSection.value ? typingSpeed / 2 : typingSpeed;
      typingTimeout = setTimeout(typeNextChar, currentSpeed);
    } else {
      // Cuando el comando está completo
      charIndex.value = 0;
      commandIndex.value++;

      // Scroll automático al completar un comando
      scrollTerminalToBottom();

      // Pausa entre comandos - más corta para las líneas del JSON
      const delayTime = isJsonSection.value
        ? 100
        : commandIndex.value % 2 === 0
        ? 500
        : 1000;
      typingTimeout = setTimeout(typeNextChar, delayTime);
    }
  };

  typeNextChar();
};

// Efecto intermitente del cursor - solo para compatibilidad
// La animación real ahora está manejada por CSS
setInterval(() => {
  terminalCursor.value = !terminalCursor.value;
}, 500);

// Variables para las partículas flotantes
const particles = ref<
  {
    x: number;
    y: number;
    size: number;
    speedX: number;
    speedY: number;
    opacity: number;
    color: string;
  }[]
>([]);
let particleInterval: number | null = null;

// Inicializar partículas
const initParticles = () => {
  // Crear partículas iniciales
  for (let i = 0; i < 30; i++) {
    particles.value.push({
      x: Math.random() * 100,
      y: Math.random() * 100,
      size: Math.random() * 5 + 1,
      speedX: (Math.random() - 0.5) * 0.5,
      speedY: (Math.random() - 0.5) * 0.5,
      opacity: Math.random() * 0.7 + 0.3,
      color: getRandomColor(),
    });
  }

  // Animar partículas
  particleInterval = setInterval(() => {
    particles.value = particles.value.map((p) => {
      p.x += p.speedX;
      p.y += p.speedY;

      // Hacer que reboten en los bordes
      if (p.x < 0 || p.x > 100) p.speedX *= -1;
      if (p.y < 0 || p.y > 100) p.speedY *= -1;

      return p;
    });
  }, 50);
};

// Función para generar colores aleatorios para partículas
const getRandomColor = () => {
  const colors = [
    "#3B82F6", // blue-500
    "#6366F1", // indigo-500
    "#8B5CF6", // violet-500
    "#EC4899", // pink-500
    "#10B981", // emerald-500
  ];
  return colors[Math.floor(Math.random() * colors.length)];
};

// Función para forzar el scroll al fondo
const scrollToBottom = () => {
  if (terminalRef.value) {
    terminalRef.value.scrollTo({
      top: terminalRef.value.scrollHeight,
      behavior: "smooth",
    });
    // Después de hacer scroll manual al fondo, reactivamos el scroll automático
    isAtBottom.value = true;
  }
};
</script>

<style>
/* Scroll suave para toda la página */
html {
  scroll-behavior: smooth;
}

/* Offset para compensar la altura del header al hacer scroll */
#featured-projects {
  scroll-margin-top: 40px;
}

/* Estos son patrones de cuadrícula que requieren CSS personalizado */
.bg-grid-pattern {
  background-image: linear-gradient(
      to right,
      rgba(59, 130, 246, 0.3) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgba(59, 130, 246, 0.3) 1px, transparent 1px),
    linear-gradient(to right, rgba(59, 130, 246, 0.4) 2px, transparent 2px),
    linear-gradient(to bottom, rgba(59, 130, 246, 0.4) 2px, transparent 2px);
  background-size: 30px 30px, 30px 30px, 150px 150px, 150px 150px;
  background-position: center center;
}

.dark .bg-grid-pattern {
  background-image: linear-gradient(
      to right,
      rgba(96, 165, 250, 0.25) 1px,
      transparent 1px
    ),
    linear-gradient(to bottom, rgba(96, 165, 250, 0.25) 1px, transparent 1px),
    linear-gradient(to right, rgba(96, 165, 250, 0.35) 2px, transparent 2px),
    linear-gradient(to bottom, rgba(96, 165, 250, 0.35) 2px, transparent 2px);
  background-size: 30px 30px, 30px 30px, 150px 150px, 150px 150px;
}

/* Animaciones para los círculos gradientes */
@keyframes blob {
  0% {
    transform: scale(1) translate(0px, 0px) rotate(0deg);
    border-radius: 60% 40% 70% 30%;
  }
  25% {
    transform: scale(1.05) translate(15px, -25px) rotate(2deg);
    border-radius: 70% 30% 50% 50%;
  }
  50% {
    transform: scale(1.1) translate(20px, 10px) rotate(5deg);
    border-radius: 30% 70% 40% 60%;
  }
  75% {
    transform: scale(0.95) translate(-15px, 20px) rotate(2deg);
    border-radius: 50% 50% 30% 70%;
  }
  100% {
    transform: scale(1) translate(0px, 0px) rotate(0deg);
    border-radius: 60% 40% 70% 30%;
  }
}

.animate-blob {
  animation: blob 30s ease-in-out infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.animation-delay-6000 {
  animation-delay: 6s;
}

.animation-delay-3000 {
  animation-delay: 3s;
}

.animation-delay-5000 {
  animation-delay: 5s;
}

/* Efecto 3D - no tiene equivalente en Tailwind */
.image-container {
  perspective: 1500px;
}

.grid-bg-container {
  transform-style: preserve-3d;
  will-change: transform;
}

.image-wrapper {
  transform-style: preserve-3d;
}

/* Animaciones y keyframes */
@keyframes blinkCursor {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0;
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes skillAnimation {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}

@keyframes float {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
  100% {
    transform: translateY(0px);
  }
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

@keyframes blinkDot {
  0%,
  100% {
    opacity: 1;
  }
  50% {
    opacity: 0.3;
  }
}

/* Animación de despliegue para el contenido expandible */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slideDown {
  animation: slideDown 0.5s ease-out forwards;
}

/* Aplicación de animaciones */
.fade-in {
  animation: fadeIn 0.5s ease-in-out;
}

.skill-bar-fill {
  animation: skillAnimation 1.5s ease-out forwards;
}

.floating {
  animation: float 6s ease-in-out infinite;
}

.rotating {
  animation: rotate 20s linear infinite;
}

/* Terminal cursor */
.terminal-cursor {
  animation: blinkCursor 1s step-end infinite;
}

/* Hexágono - forma compleja que requiere CSS puro */
/* Eliminando la parte del CSS del hexágono */

/* Estilos para el indicador de puntos parpadeantes */
.blinking-dot {
  animation: blinkDot 1s infinite;
}

/* Altura fija para terminal */
.h-terminal {
  height: 550px;
}

/* Altura fija para contenedores principales */
.h-content-box {
  height: 550px;
  overflow-y: auto;
}

/* Estilos personalizados para la scrollbar de la terminal */
.terminal-scrollbar {
  /* Firefox */
  scrollbar-width: thin;
  scrollbar-color: rgba(100, 100, 100, 0.6) rgba(0, 0, 0, 0.1);
}

/* Estilos para Chrome, Edge y Safari */
.terminal-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.terminal-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
}

.terminal-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(120, 120, 120, 0.6);
  border-radius: 3px;
  transition: background 0.3s ease;
}

.terminal-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(140, 140, 140, 0.7);
}

/* Para darle un efecto al scroll cuando se usa */
.terminal-scrollbar::-webkit-scrollbar-thumb:active {
  background: rgba(160, 160, 160, 0.8);
  box-shadow: 0 0 6px rgba(180, 180, 180, 0.4);
}

/* Transiciones para el modal de imágenes */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s, transform 0.4s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

/* Animación para el clic en las imágenes */
@keyframes pulseClick {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

.pulse-on-click {
  animation: pulseClick 0.3s ease-out;
}

/* Efecto de brillo al pasar por encima de las imágenes del modal */
.hover-glow:hover {
  box-shadow: 0 0 25px rgba(255, 255, 255, 0.15);
}

/* Animación para cambio de pestañas */
.animate-fadeIn {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes float {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
  100% {
    transform: translateY(0px);
  }
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>

<template>
  <div
    class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-300 relative overflow-hidden"
  >
    <!-- Círculos gradientes deformes en el fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <!-- Círculo superior izquierda -->
      <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] bg-gradient-to-br from-blue-400/25 to-purple-500/25 dark:from-blue-500/30 dark:to-purple-600/30 rounded-full blur-[120px] animate-blob"></div>
      
      <!-- Círculo superior derecha -->
      <div class="absolute -top-[5%] right-[20%] w-[40%] h-[40%] bg-gradient-to-br from-pink-400/20 to-purple-500/20 dark:from-pink-500/30 dark:to-purple-600/30 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>
      
      <!-- Círculo central izquierda -->
      <div class="absolute top-[40%] -left-[10%] w-[55%] h-[55%] bg-gradient-to-br from-green-400/15 to-teal-500/15 dark:from-green-500/25 dark:to-teal-600/25 rounded-full blur-[120px] animate-blob animation-delay-4000"></div>
      
      <!-- Círculo inferior derecha -->
      <div class="absolute bottom-[10%] right-[10%] w-[50%] h-[50%] bg-gradient-to-br from-orange-400/15 to-amber-500/15 dark:from-orange-500/25 dark:to-amber-600/25 rounded-full blur-[130px] animate-blob animation-delay-6000"></div>
      
      <!-- Círculo central derecha -->
      <div class="absolute top-[35%] right-[5%] w-[45%] h-[45%] bg-gradient-to-br from-cyan-400/15 to-blue-500/15 dark:from-cyan-500/30 dark:to-blue-600/30 rounded-full blur-[90px] animate-blob animation-delay-3000"></div>
      
      <!-- Círculo inferior izquierda -->
      <div class="absolute bottom-[5%] left-[20%] w-[40%] h-[40%] bg-gradient-to-br from-purple-400/15 to-indigo-500/15 dark:from-purple-500/25 dark:to-indigo-600/25 rounded-full blur-[100px] animate-blob animation-delay-5000"></div>
    </div>

    <!-- Header with Dark Mode Toggle and Language Selector -->
    <header
      class="fixed top-0 left-0 right-0 z-50 px-4 py-4 transition-all duration-300"
      :class="[
        isScrolled
          ? 'bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-md py-3'
          : 'bg-transparent py-6',
      ]"
    >
      <div class="container mx-auto flex justify-between items-center">
        <div
          class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-400 dark:to-purple-500 font-extrabold transition-all duration-300"
          :class="{ 'scale-90': isScrolled }"
        >
          Joan Paneque
        </div>

        <div class="flex items-center space-x-4">
          <!-- Language selector -->
          <HeaderDropdown
            :options="languageOptions"
            :selected="locale"
            @select="changeLanguage"
            label="Seleccionar idioma"
          />

          <!-- Dark mode toggle -->
          <button
            @click="toggleDarkMode"
            class="p-2 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur shadow-md hover:shadow-lg transition-all duration-300"
            aria-label="Toggle dark mode"
          >
            <svg
              v-if="isDarkMode"
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6 text-yellow-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6 text-indigo-700"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
              />
            </svg>
          </button>
        </div>
      </div>
    </header>

    <main class="container mx-auto px-4 pb-20 pt-24">
      <!-- Hero Section -->
      <section class="py-16 md:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
          <!-- Orden en escritorio normal (1º texto, 2º imagen), en móvil invertido (1º imagen, 2º texto) -->
          <div class="space-y-6 order-2 md:order-1">
            <h1
              class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-800 dark:text-white"
            >
              {{ t("hero.greeting") }}
              <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-400 dark:to-purple-500 font-extrabold"
                >{{ t("hero.title") }}</span
              >
              {{ t("hero.specialization") }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
              {{ t("hero.description") }}
            </p>
            <div>
              <a
                href="#featured-projects"
                class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
              >
                {{ t("cta.viewProjects") }}
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 ml-2"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M14 5l7 7m0 0l-7 7m7-7H3"
                  />
                </svg>
              </a>
            </div>
          </div>
          <div
            ref="imageContainerRef"
            class="relative order-1 md:order-2 image-container"
          >
            <!-- Todo el contenedor con efecto 3D -->
            <div
              class="grid-bg-container rounded-3xl overflow-hidden transition-transform duration-200 ease-out"
              :style="{
                transform: `rotateX(${tiltX}deg) rotateY(${tiltY}deg)`,
                boxShadow: '0 20px 40px -10px rgba(0, 0, 0, 0.3)',
              }"
            >
              <!-- Fondo moderno con múltiples efectos -->
              <div class="overflow-hidden rounded-3xl">
                <!-- Gradiente de fondo base -->
                <div
                  class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-purple-600/20 dark:from-blue-500/30 dark:to-purple-600/30 backdrop-blur-sm layer-3d transition-transform duration-200 ease-out"
                  :style="{
                    transform: 'translateZ(15px)',
                  }"
                ></div>

                <!-- Patrón de cuadrícula (grid) tech con mayor visibilidad -->
                <div
                  class="absolute inset-0 bg-grid-pattern opacity-40 dark:opacity-30 layer-3d transition-transform duration-200 ease-out"
                  :style="{
                    transform: 'translateZ(25px)',
                  }"
                ></div>
              </div>

              <!-- Contenedor de la imagen sin padding -->
              <div
                class="relative h-full flex items-center justify-center z-10 image-wrapper rounded-3xl overflow-hidden transition-transform duration-200 ease-out"
              >
                <img
                  src="/uploads/joan.png"
                  alt="Joan - Desarrollador Fullstack"
                  class="w-full object-cover rounded-xl drop-shadow-2xl max-h-[70vh] layer-3d transition-transform duration-200 ease-out"
                  :style="{
                    transform: 'translateZ(35px)',
                  }"
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- About Me Section - Versión disruptiva -->
      <section class="py-16 relative overflow-hidden">
        <!-- Partículas flotantes de fondo - eliminadas para un diseño más limpio -->

        <div class="max-w-7xl mx-auto px-4 relative z-10">
          <h2
            class="text-3xl sm:text-4xl font-bold mb-8 text-center text-gray-800 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-400 dark:to-purple-500 font-extrabold"
          >
            {{ t("sections.aboutMe") }}
          </h2>

          <!-- Contenedor principal para desktop con efecto glassmorphism -->
          <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-stretch">
            <!-- Terminal interactiva (a la izquierda en desktop, más amplia) -->
            <div class="md:col-span-2">
              <div
                class="flex flex-col relative rounded-lg overflow-hidden border border-white/10 bg-black/85 shadow-[0_10px_25px_-5px_rgba(0,0,0,0.3),_0_10px_10px_-5px_rgba(0,0,0,0.2)]"
                style="height: 550px;"
              >
                <div
                  class="flex items-center h-9 flex-shrink-0 px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 dark:from-gray-800 dark:to-gray-900"
                >
                  <div class="h-3 w-3 rounded-full bg-red-500 mr-1.5"></div>
                  <div class="h-3 w-3 rounded-full bg-yellow-500 mr-1.5"></div>
                  <div class="h-3 w-3 rounded-full bg-green-500 mr-1.5"></div>
                  <div class="ml-4 text-sm text-white font-medium">joan@portfolio:~</div>
                </div>
                <div
                  class="relative flex-1 flex flex-col overflow-hidden h-[calc(100%-36px)]"
                >
                  <div
                    ref="terminalRef"
                    class="p-4 flex-1 leading-tight overflow-y-auto h-full max-h-full bg-gray-900 text-sm text-green-400 font-mono terminal-scrollbar"
                  >
                    <!-- Líneas de la terminal -->
                    <div
                      v-for="(line, index) in terminalLines"
                      :key="index"
                      class="mb-1 flex relative items-start min-h-4 flex-wrap w-full"
                    >
                      <div class="w-full">
                        <pre
                          class="font-mono inline whitespace-pre-wrap break-words max-w-full m-0 text-sm"
                          v-if="
                            line &&
                            (line.includes('{') ||
                              line.includes('[') ||
                              line.includes(']') ||
                              line.includes('}') ||
                              line.includes('&quot;'))
                          "
                          v-html="formatJsonSyntax(line)"
                        ></pre>
                        <pre
                          class="font-mono inline whitespace-pre-wrap break-words max-w-full m-0 text-sm"
                          v-else
                          >{{ line }}</pre
                        >
                      </div>

                      <!-- Cursor que solo aparece en la última línea -->
                      <div
                        v-if="index === terminalLines.length - 1"
                        class="inline-flex items-start h-4 w-2 mt-px relative"
                      >
                        <span
                          class="absolute top-0 left-0 block w-2 h-4 bg-[#A8FF60] terminal-cursor"
                        ></span>
                      </div>
                    </div>
                  </div>

                  <!-- Indicador de scroll fijo de la terminal -->
                  <div
                    class="absolute bottom-4 right-4 bg-[rgba(20,20,20,0.95)] text-[#A8FF60] text-xs py-2 px-3 rounded-md cursor-pointer opacity-0 transition-all duration-300 z-[30] font-mono shadow-lg border border-[rgba(168,255,96,0.3)] flex items-center gap-1.5 pointer-events-auto hover:bg-[rgba(30,30,30,0.98)] hover:border-[rgba(168,255,96,0.5)] transform hover:scale-105"
                    :class="{ 'opacity-100': userScrolled && !isAtBottom }"
                    @click="scrollToBottom"
                  >
                    <span
                      class="inline-block w-1.5 h-1.5 bg-[#A8FF60] rounded-full blinking-dot"
                    ></span>
                    <span>Nuevo output</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nuevo contenido "Sobre mí" con diseño atractivo -->
            <div class="md:col-span-3 relative" style="height: 550px;">
              <!-- Fondo con gradiente y efecto glassmorphism -->
              <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-600/10 dark:from-blue-500/10 dark:to-purple-600/20 backdrop-blur-md rounded-2xl"></div>
              
              <!-- Formas geométricas decorativas eliminadas para un diseño más limpio -->
              
              <!-- Contenido principal -->
              <div class="relative h-full z-10 p-6 overflow-y-auto terminal-scrollbar"
                   style="height: 100%; max-height: 550px;"
              >
                <!-- Cabecera "Sobre mí" -->
                <div class="flex items-center mb-6">
                  <div class="p-2 bg-blue-100 dark:bg-blue-500/20 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ t("profile.aboutMe") }}</h3>
                </div>
                
                <!-- Sistema de pestañas -->
                <div class="mb-6">
                  <div class="flex border-b border-gray-200 dark:border-gray-700">
                    <button 
                      @click="activeTab = 'about'" 
                      class="py-2 px-4 focus:outline-none transition-colors duration-200"
                      :class="activeTab === 'about' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-500 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-300'"
                    >
                      {{ t("tabs.about") || "Sobre mí" }}
                    </button>
                    <button 
                      @click="activeTab = 'stack'" 
                      class="py-2 px-4 focus:outline-none transition-colors duration-200"
                      :class="activeTab === 'stack' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-500 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-300'"
                    >
                      {{ t("tabs.techStack") || "Stack Tecnológico" }}
                    </button>
                    <button 
                      @click="activeTab = 'interests'" 
                      class="py-2 px-4 focus:outline-none transition-colors duration-200"
                      :class="activeTab === 'interests' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-500 font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-300'"
                    >
                      {{ t("tabs.interests") || "Intereses" }}
                    </button>
                  </div>
                </div>
                
                <!-- Contenido de pestaña: Sobre mí -->
                <div v-if="activeTab === 'about'" class="animate-fadeIn">
                  <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm p-5 rounded-xl shadow-sm border border-white/20 dark:border-gray-600/20">
                    <p class="text-gray-700 dark:text-gray-200 mb-4">
                      {{ t("sections.aboutDescription") }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-200">
                      {{ t("sections.aboutExperience") || "Además de mi experiencia técnica, me apasiona el aprendizaje continuo y la colaboración en equipos multidisciplinarios. Creo firmemente en el enfoque centrado en el usuario y en crear soluciones que no solo funcionen bien sino que también ofrezcan una excelente experiencia." }}
                    </p>
                  </div>
                </div>
                
                <!-- Contenido de pestaña: Stack tecnológico -->
                <div v-if="activeTab === 'stack'" class="animate-fadeIn">
                  <!-- Categorías de tecnologías -->
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Frontend -->
                    <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm p-4 rounded-lg">
                      <div class="flex items-center mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-md bg-blue-100 dark:bg-blue-500/20 mr-3">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                          </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ t("skills.frontend") || "Frontend" }}</span>
                      </div>
                      <div class="flex flex-wrap gap-2">
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">Vue.js</span>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">React</span>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">Lit Element</span>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">Astro</span>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">Angular</span>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">TypeScript</span>
                        <span class="text-xs px-2.5 py-1 bg-blue-50 dark:bg-blue-800/30 text-blue-600 dark:text-blue-300 rounded-md">Tailwind CSS</span>
                      </div>
                    </div>
                    
                    <!-- Backend -->
                    <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm p-4 rounded-lg">
                      <div class="flex items-center mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-md bg-green-100 dark:bg-green-500/20 mr-3">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                          </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ t("skills.backend") || "Backend" }}</span>
                      </div>
                      <div class="flex flex-wrap gap-2">
                        <span class="text-xs px-2.5 py-1 bg-green-50 dark:bg-green-800/30 text-green-600 dark:text-green-300 rounded-md">Laravel</span>
                        <span class="text-xs px-2.5 py-1 bg-green-50 dark:bg-green-800/30 text-green-600 dark:text-green-300 rounded-md">Node.js</span>
                        <span class="text-xs px-2.5 py-1 bg-green-50 dark:bg-green-800/30 text-green-600 dark:text-green-300 rounded-md">Express</span>
                        <span class="text-xs px-2.5 py-1 bg-green-50 dark:bg-green-800/30 text-green-600 dark:text-green-300 rounded-md">PHP</span>
                        <span class="text-xs px-2.5 py-1 bg-green-50 dark:bg-green-800/30 text-green-600 dark:text-green-300 rounded-md">Java</span>
                        <span class="text-xs px-2.5 py-1 bg-green-50 dark:bg-green-800/30 text-green-600 dark:text-green-300 rounded-md">C/C++</span>
                      </div>
                    </div>
                    
                    <!-- Bases de Datos -->
                    <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm p-4 rounded-lg">
                      <div class="flex items-center mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-md bg-indigo-100 dark:bg-indigo-500/20 mr-3">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                          </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ t("skills.databases") || "Bases de Datos" }}</span>
                      </div>
                      <div class="flex flex-wrap gap-2">
                        <span class="text-xs px-2.5 py-1 bg-indigo-50 dark:bg-indigo-800/30 text-indigo-600 dark:text-indigo-300 rounded-md">MySQL</span>
                        <span class="text-xs px-2.5 py-1 bg-indigo-50 dark:bg-indigo-800/30 text-indigo-600 dark:text-indigo-300 rounded-md">MariaDB</span>
                        <span class="text-xs px-2.5 py-1 bg-indigo-50 dark:bg-indigo-800/30 text-indigo-600 dark:text-indigo-300 rounded-md">SQLite</span>
                      </div>
                    </div>
                    
                    <!-- DevOps & Herramientas -->
                    <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm p-4 rounded-lg">
                      <div class="flex items-center mb-3">
                        <div class="w-8 h-8 flex items-center justify-center rounded-md bg-purple-100 dark:bg-purple-500/20 mr-3">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                        </div>
                        <span class="font-semibold text-gray-800 dark:text-white">{{ t("skills.devops") || "DevOps & Tools" }}</span>
                      </div>
                      <div class="flex flex-wrap gap-2">
                        <span class="text-xs px-2.5 py-1 bg-purple-50 dark:bg-purple-800/30 text-purple-600 dark:text-purple-300 rounded-md">Docker</span>
                        <span class="text-xs px-2.5 py-1 bg-purple-50 dark:bg-purple-800/30 text-purple-600 dark:text-purple-300 rounded-md">Git</span>
                        <span class="text-xs px-2.5 py-1 bg-purple-50 dark:bg-purple-800/30 text-purple-600 dark:text-purple-300 rounded-md">CI/CD</span>
                        <span class="text-xs px-2.5 py-1 bg-purple-50 dark:bg-purple-800/30 text-purple-600 dark:text-purple-300 rounded-md">Linux</span>
                        <span class="text-xs px-2.5 py-1 bg-purple-50 dark:bg-purple-800/30 text-purple-600 dark:text-purple-300 rounded-md">GitHub Actions</span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Contenido de pestaña: Intereses -->
                <div v-if="activeTab === 'interests'" class="animate-fadeIn">
                  <div class="bg-white/50 dark:bg-gray-700/50 backdrop-blur-sm p-5 rounded-xl mb-6 shadow-sm border border-white/20 dark:border-gray-600/20">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center flex-shrink-0 mr-4">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-medium text-gray-800 dark:text-white mb-1">{{ t("interests.running") || "Correr" }}</h5>
                          <p class="text-sm text-gray-600 dark:text-gray-300">{{ t("interests.runningDesc") || "Me encanta correr para mantenerme en forma, despejar la mente y establecer nuevos retos personales." }}</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0 mr-4">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-medium text-gray-800 dark:text-white mb-1">{{ t("interests.ai") || "Inteligencia Artificial" }}</h5>
                          <p class="text-sm text-gray-600 dark:text-gray-300">{{ t("interests.aiDesc") || "Estoy fascinado por los avances en IA y me gusta experimentar con herramientas y modelos de aprendizaje automático." }}</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center flex-shrink-0 mr-4">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-medium text-gray-800 dark:text-white mb-1">{{ t("interests.entrepreneurship") || "Emprendimiento" }}</h5>
                          <p class="text-sm text-gray-600 dark:text-gray-300">{{ t("interests.entrepreneurshipDesc") || "Disfruto convirtiendo ideas innovadoras en proyectos viables y aprendiendo sobre desarrollo de negocios." }}</p>
                        </div>
                      </div>
                      
                      <div class="flex items-start">
                        <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center flex-shrink-0 mr-4">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 104 0 2 2 0 012-2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                        </div>
                        <div>
                          <h5 class="font-medium text-gray-800 dark:text-white mb-1">{{ t("interests.traveling") || "Traveling" }}</h5>
                          <p class="text-sm text-gray-600 dark:text-gray-300">{{ t("interests.travelingDesc") || "Viajar me permite conectar con diferentes culturas y ampliar mi perspectiva global." }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex justify-center">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-purple-500/20 to-blue-500/20 text-gray-700 dark:text-gray-200">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                      </svg>
                      <span>{{ t("interests.quote") || "La curiosidad es el motor que impulsa mi desarrollo profesional y personal" }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Featured Projects -->
      <section id="featured-projects" class="py-16">
        <h2 class="text-3xl sm:text-4xl font-bold mb-8 text-center text-gray-800 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-400 dark:to-purple-500 font-extrabold">
          {{ t("sections.featuredProjects") }}
        </h2>
        <div class="max-w-7xl mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <!-- Project Trueta - NUEVO DISEÑO MODERNO Y DISRUPTIVO -->
            <FeaturedProject
              :title="t('projects.projectTrueta')"
              :description="t('projects.truetaDescription')"
              :technologies="[
                { name: 'Laravel', color: 'bg-blue-400' },
                { name: 'Vue', color: 'bg-green-400' },
                { name: 'Tailwind', color: 'bg-purple-400' }
              ]"
              projectUrl="/projects/trueta"
              :images="galleryImages"
              :awards="[
                {
                  title: t('awards.girona.title') || 'Premio de la Universitat de Girona',
                  description: t('awards.girona.description') || 'Mejor proyecto en ámbito STEM'
                },
                {
                  title: t('awards.bonaplata.title') || 'Premi Bonaplata',
                  description: t('awards.bonaplata.description') || 'Museo de Ciencia y Técnica de Cataluña'
                }
              ]"
              :buttonText="t('cta.goToProject') || 'Ir al proyecto'"
            >
              <template #metrics>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <span class="inline-block w-2 h-2 rounded-full bg-cyan-500 dark:bg-cyan-300 mr-2 animate-pulse"></span>
                  {{ t("projects.timeSaved") || "Tiempo ahorrado en tiempo real" }}
                </h4>
                
                <!-- Diseño tipo dashboard de métricas -->
                <div class="grid grid-cols-4 gap-3">
                  <div class="bg-white/60 dark:bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-gray-300 dark:border-white/10 group-hover:border-gray-400 dark:group-hover:border-white/20 transition-all duration-300">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ savedTimeFormatted.days }}</div>
                    <div class="text-xs uppercase tracking-wider text-gray-900 dark:text-cyan-100 opacity-90">
                      {{ t("projects.days") || "Días" }}
                    </div>
                  </div>
                  <div class="bg-white/60 dark:bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-gray-300 dark:border-white/10 group-hover:border-gray-400 dark:group-hover:border-white/20 transition-all duration-300">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ savedTimeFormatted.hours }}</div>
                    <div class="text-xs uppercase tracking-wider text-gray-900 dark:text-cyan-100 opacity-90">
                      {{ t("projects.hours") || "Horas" }}
                    </div>
                  </div>
                  <div class="bg-white/60 dark:bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-gray-300 dark:border-white/10 group-hover:border-gray-400 dark:group-hover:border-white/20 transition-all duration-300">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ savedTimeFormatted.minutes }}</div>
                    <div class="text-xs uppercase tracking-wider text-gray-900 dark:text-cyan-100 opacity-90">
                      {{ t("projects.minutes") || "Minutos" }}
                    </div>
                  </div>
                  <div class="bg-white/60 dark:bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-gray-300 dark:border-white/10 group-hover:border-gray-400 dark:group-hover:border-white/20 transition-all duration-300">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ savedTimeFormatted.seconds }}</div>
                    <div class="text-xs uppercase tracking-wider text-gray-900 dark:text-cyan-100 opacity-90">
                      {{ t("projects.seconds") || "Segundos" }}
                    </div>
                  </div>
                </div>
                
                <!-- Subtítulo con la tasa de ahorro -->
                <p class="text-xs text-gray-700 dark:text-cyan-100 opacity-80 mt-2 text-left">
                  {{ t("projects.savingRate") || "Ahorrando 0.1925 segundos por cada segundo desde el 1 de junio de 2024" }}
                </p>
                
                <!-- Estadística de operaciones -->
                <div class="mt-8">
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <span class="inline-block w-2 h-2 rounded-full bg-blue-600 dark:bg-blue-400 mr-2 animate-pulse"></span>
                    {{ t("projects.surgeries") || "Operaciones realizadas" }}
                  </h4>
                  
                  <div class="bg-gradient-to-r from-blue-200/60 to-indigo-200/60 dark:from-blue-500/30 dark:to-indigo-500/30 rounded-xl p-4 border border-gray-300 dark:border-white/10">
                    <div class="flex justify-between items-center">
                      <div>
                        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ Math.floor(operacionesRealizadas) }}</p>
                      </div>
                      <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-200/80 dark:bg-blue-500/20 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-900 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Tasa de operaciones -->
                  <p class="text-xs text-gray-700 dark:text-cyan-100 opacity-80 mt-2 text-left">
                    {{ t("projects.surgeriesRate") || "15.000 operaciones por año desde el 24 de julio de 2024" }}
                  </p>
                </div>
              </template>
            </FeaturedProject>

            <!-- Proyecto ObservaAI - Sistema de alertas IA -->
            <FeaturedProject
              :title="t('projects.projectObservaAI') || 'ObservaAI'"
              :description="t('projects.observaAIDescription') || 'Sistema de inteligencia artificial que te permite crear alertas personalizadas con lenguaje natural. Define tus propios triggers como &quot;Avísame cuando Bitcoin baje un 5% en menos de 3 minutos&quot; o &quot;Notifícame cuando Rockstar Games publique noticias sobre GTA VI&quot; y recibe alertas instantáneas.'"
              :technologies="[
                { name: 'Python', color: 'bg-blue-400' },
                { name: 'React', color: 'bg-cyan-400' },
                { name: 'TensorFlow', color: 'bg-orange-400' },
                { name: 'NLP', color: 'bg-indigo-400' }
              ]"
              projectUrl="/projects/observaai"
              :images="observaAIGalleryImages"
              :gradientColors="{
                from: 'from-indigo-300/60 dark:from-indigo-500/40',
                via: 'via-purple-300/55 dark:via-purple-600/35',
                to: 'to-pink-300/60 dark:to-pink-700/40'
              }"
              :buttonText="t('cta.goToProject') || 'Ir al proyecto'"
            >
              <template #metrics>
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <span class="inline-block w-2 h-2 rounded-full bg-pink-600 dark:bg-pink-300 mr-2 animate-pulse"></span>
                  {{ t("projects.alertStats") || "Estadísticas en tiempo real" }}
                </h4>
                
                <!-- Métricas del proyecto ObservaAI -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                  <div class="bg-white/70 dark:bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-gray-300 dark:border-white/10 group-hover:border-gray-400 dark:group-hover:border-white/20 transition-all duration-300">
                    <div class="flex justify-between items-center">
                      <div>
                        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">98.7%</p>
                        <div class="text-xs uppercase tracking-wider text-gray-900 dark:text-pink-100 opacity-90 mt-1">
                          {{ t("projects.accuracy") || "Precisión" }}
                        </div>
                      </div>
                      <div class="w-12 h-12 flex items-center justify-center rounded-full bg-pink-200/60 dark:bg-pink-500/20 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-900 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-white/70 dark:bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-gray-300 dark:border-white/10 group-hover:border-gray-400 dark:group-hover:border-white/20 transition-all duration-300">
                    <div class="flex justify-between items-center">
                      <div>
                        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ Math.floor(observaAIAnalysisCount) }}</p>
                        <div class="text-xs uppercase tracking-wider text-gray-900 dark:text-pink-100 opacity-90 mt-1">
                          {{ t("projects.aiAnalysis") || "Análisis realizados" }}
                        </div>
                      </div>
                      <div class="w-12 h-12 flex items-center justify-center rounded-full bg-purple-200/60 dark:bg-purple-500/20 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-900 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Casos de uso populares -->
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                  <span class="inline-block w-2 h-2 rounded-full bg-purple-600 dark:bg-purple-400 mr-2 animate-pulse"></span>
                  {{ t("projects.popularCases") || "Casos de uso populares" }}
                </h4>
                
                <div class="space-y-3">
                  <div class="bg-gradient-to-r from-purple-200/60 to-pink-200/60 dark:from-purple-500/20 dark:to-pink-500/20 rounded-xl p-3 border border-gray-300 dark:border-white/10 backdrop-blur-sm">
                    <div class="flex items-start">
                      <div class="w-8 h-8 flex-shrink-0 bg-purple-300/80 dark:bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-700 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                      </div>
                      <div class="text-gray-900 dark:text-white text-sm">
                        <span class="font-medium">{{ t("projects.cryptoAlerts") || "Alertas de criptomonedas" }}</span>
                        <p class="text-gray-700 dark:text-white/80 text-xs mt-1">{{ t("projects.cryptoDescription") || "Notificaciones en tiempo real sobre cambios bruscos en el precio de criptomonedas." }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-gradient-to-r from-purple-200/60 to-pink-200/60 dark:from-purple-500/20 dark:to-pink-500/20 rounded-xl p-3 border border-gray-300 dark:border-white/10 backdrop-blur-sm">
                    <div class="flex items-start">
                      <div class="w-8 h-8 flex-shrink-0 bg-purple-300/80 dark:bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-700 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                      </div>
                      <div class="text-gray-900 dark:text-white text-sm">
                        <span class="font-medium">{{ t("projects.newsAlerts") || "Alertas de noticias" }}</span>
                        <p class="text-gray-700 dark:text-white/80 text-xs mt-1">{{ t("projects.newsDescription") || "Monitoreo de fuentes de noticias con detección contextual inteligente sobre temas específicos." }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="bg-gradient-to-r from-purple-200/60 to-pink-200/60 dark:from-purple-500/20 dark:to-pink-500/20 rounded-xl p-3 border border-gray-300 dark:border-white/10 backdrop-blur-sm">
                    <div class="flex items-start">
                      <div class="w-8 h-8 flex-shrink-0 bg-purple-300/80 dark:bg-purple-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-700 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                      </div>
                      <div class="text-gray-900 dark:text-white text-sm">
                        <span class="font-medium">{{ t("projects.weatherAlerts") || "Alertas meteorológicas" }}</span>
                        <p class="text-gray-700 dark:text-white/80 text-xs mt-1">{{ t("projects.weatherDescription") || "Predicciones personalizadas sobre fenómenos meteorológicos específicos en ubicaciones concretas." }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </FeaturedProject>
          </div>
        </div>
      </section>

      <!-- Services -->
      <section class="py-16">
        <h2 class="text-3xl sm:text-4xl font-bold mb-8 text-center text-gray-800 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-400 dark:to-purple-500 font-extrabold">
          {{ t("sections.services") }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Web Development -->
          <div
            class="bg-gradient-to-br from-blue-500/10 to-indigo-500/10 dark:from-blue-800/20 dark:to-indigo-800/20 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
          >
            <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-xl w-fit mb-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8 text-blue-600 dark:text-blue-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">
              {{ t("services.webDev") }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300">
              {{ t("services.webDevDescription") }}
            </p>
          </div>

          <!-- UI/UX Design -->
          <div
            class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 dark:from-purple-800/20 dark:to-pink-800/20 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
          >
            <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-xl w-fit mb-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8 text-purple-600 dark:text-purple-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"
                />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">
              {{ t("services.uiUxDesign") }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300">
              {{ t("services.uiUxDescription") }}
            </p>
          </div>

          <!-- API Integrations -->
          <div
            class="bg-gradient-to-br from-green-500/10 to-teal-500/10 dark:from-green-800/20 dark:to-teal-800/20 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
          >
            <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-xl w-fit mb-4">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8 text-green-600 dark:text-green-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">
              {{ t("services.apiIntegrations") }}
            </h3>
            <p class="text-gray-600 dark:text-gray-300">
              {{ t("services.apiDescription") }}
            </p>
          </div>
        </div>
      </section>

      <!-- Client Logos -->
      <section class="py-16">
        <h2 class="text-3xl sm:text-4xl font-bold mb-8 text-center text-gray-800 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-700 dark:from-blue-400 dark:to-purple-500 font-extrabold">
          {{ t("sections.collaborations") }}
        </h2>
        <div
          class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm p-8 rounded-2xl shadow-lg"
        >
          <div class="flex flex-wrap justify-center items-center gap-8">
            <!-- Placeholder logos -->
            <div
              class="w-32 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400"
            >
              {{ t("collaborations.logo1") || "Logo 1" }}
            </div>
            <div
              class="w-32 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400"
            >
              {{ t("collaborations.logo2") || "Logo 2" }}
            </div>
            <div
              class="w-32 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400"
            >
              {{ t("collaborations.logo3") || "Logo 3" }}
            </div>
            <div
              class="w-32 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-500 dark:text-gray-400"
            >
              {{ t("collaborations.logo4") || "Logo 4" }}
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm py-6">
      <div class="container mx-auto px-4 text-center text-gray-600 dark:text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} Joan. {{ t("footer.rights") }}</p>
      </div>
    </footer>
  </div>
</template>
