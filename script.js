
//Imagenes que se desplazan para la derecha y se devuelven(ya estoy cansado unu)
const carouselContainer = document.querySelector('.carousel-container');
if (carouselContainer) { 
let slideIndex = 0;

function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide img');
    if (index >= slides.length) slideIndex = 0;
    if (index < 0) slideIndex = slides.length - 1;
    document.querySelector('.carousel-slide').style.transform = `translateX(${-slideIndex * 100}%)`;
}

function moveSlide(n) {
    slideIndex += n;
    showSlide(slideIndex);
}

// Inicia en la primera imagen:
showSlide(slideIndex);

//Tiempo que pasa cada imagen xd:
setInterval(() => {
    moveSlide(1);
}, 5000); 
}

const ciudadesPorDepartamento = {
    'ANTIOQUIA': ['Medellín', 'Rionegro', 'Bello'],
    'ATLÁNTICO': ['Barranquilla', 'Soledad', 'Malambo'],
    'BOLÍVAR': ['Cartagena', 'Magangué', 'Turbaco'],
    'BOYACÁ': ['Tunja', 'Sogamoso', 'Duitama'],
    'CALDAS': ['Manizales', 'Villamaría', 'La Dorada'],
    'CAUCA': ['Popayán', 'Santander de Quilichao', 'Piendamó'],
    'CESAR': ['Valledupar', 'Aguachica', 'Bosconia'],
    'CÓRDOBA': ['Montería', 'Cereté', 'Sahagún'],
    'CUNDINAMARCA': ['Bogotá', 'Soacha', 'Chía'],
    'MAGDALENA': ['Santa Marta', 'Ciénaga', 'Fundación'],
    'META': ['Villavicencio', 'Acacías', 'Granada'],
    'NARIÑO': ['Pasto', 'Tumaco', 'Ipiales'],
    'NORTE DE SANTANDER': ['Cúcuta', 'Ocaña', 'Villa del Rosario'],
    'RISARALDA': ['Pereira', 'Dosquebradas', 'Santa Rosa de Cabal'],
    'SANTANDER': ['Bucaramanga', 'Floridablanca', 'Girón'],
    'SUCRE': ['Sincelejo', 'Corozal', 'San Marcos'],
    'TOLIMA': ['Ibagué', 'Espinal', 'Melgar'],
    'VALLE DEL CAUCA': ['Cali', 'Palmira', 'Buenaventura'],
};

// Función para actualizar las ciudades basadas en el departamento seleccionado
function actualizarCiudades() {
    const departamentoSelect = document.getElementById('departamento');
    const ciudadSelect = document.getElementById('ciudad');
    const departamentoSeleccionado = departamentoSelect.value;

    // Limpiar las opciones previas de ciudad
    ciudadSelect.innerHTML = '<option value="">Selecciona una Ciudad</option>';

    // Añadir las nuevas opciones de ciudad si el departamento es válido
    if (departamentoSeleccionado && ciudadesPorDepartamento[departamentoSeleccionado]) {
        const ciudades = ciudadesPorDepartamento[departamentoSeleccionado];
        ciudades.forEach(ciudad => {
            const option = document.createElement('option');
            option.value = ciudad.toUpperCase();
            option.textContent = ciudad;
            ciudadSelect.appendChild(option);
        });
    }
}

// Mostrar el modal de ubicación al hacer clic en el botón de ubicación
document.querySelector('.location').addEventListener('click', function() {
    document.getElementById('location-modal').style.display = 'flex';
});

// cambiar imagen en la pagina


// Cerrar el modal al hacer clic en la "X"
document.querySelector('.close-button').addEventListener('click', function() {
    document.getElementById('location-modal').style.display = 'none';
});

// Cambiar la ubicación en el header después de guardar
document.getElementById('location-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const departamento = document.getElementById('departamento').value;
    const ciudad = document.getElementById('ciudad').value;

    if (departamento && ciudad) {
        document.querySelector('.location strong').textContent = `${ciudad}, ${departamento}`;
    }
    document.getElementById('location-modal').style.display = 'none';
});


// Navegación del catálogo
const prevBtn = document.querySelector('.catalog-prev');
const nextBtn = document.querySelector('.catalog-next');
const catalogCarousel = document.querySelector('.catalog-carousel');

prevBtn.addEventListener('click', () => {
    catalogCarousel.scrollBy({ left: -200, behavior: 'smooth' });
});

function irADetalles(producto, imagen, nuevoPrecio, antiguoPrecio, descuento, descripcion) {
    // Almacenar los datos del producto seleccionado en sessionStorage
    sessionStorage.setItem('producto', producto);
    sessionStorage.setItem('imagen', imagen);
    sessionStorage.setItem('nuevoPrecio', nuevoPrecio);
    sessionStorage.setItem('antiguoPrecio', antiguoPrecio);
    sessionStorage.setItem('descuento', descuento);
    sessionStorage.setItem('descripcion', descripcion);
    
    // Redirigir a la página de detalles (catalogo.html)
    window.location.href = 'catalogo.html';
}

nextBtn.addEventListener('click', () => {
    catalogCarousel.scrollBy({ left: 200, behavior: 'smooth' });
});

// Mostrar/ocultar el botón de volver arriba
window.onscroll = function() {
    const backToTopButton = document.getElementById('back-to-top');
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        backToTopButton.style.display = "block";
    } else {
        backToTopButton.style.display = "none";
    }
};

// Acción del botón de volver arriba
document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

//Boton de WhatsApp
function toggleOptions() {
    const options = document.getElementById('floatingOptions');
    if (options.style.display === 'none' || options.style.display === '') {
        options.style.display = 'flex';
    } else {
        options.style.display = 'none';
    }
}

//Filtro que da un error, pero que funciona normalmente
const input = document.getElementById('aña');
const catalogItems = document.querySelectorAll('.catalog-item');

input.addEventListener('input', function() {
    const searchValue = input.value.toLowerCase();

    catalogItems.forEach(item => {
        const itemName = item.querySelector('h3').textContent.toLowerCase();
        if (itemName.includes(searchValue)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});


