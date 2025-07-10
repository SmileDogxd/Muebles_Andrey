document.getElementById('user-icon').addEventListener('click', function() {
    window.location.href = 'login.php';
});

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

//cambiar detalles de imagenes descripcion y detalles de los productos
document.addEventListener('DOMContentLoaded', function() {
    const img = document.getElementById('main-product-image');
    const title = document.getElementById('product-title');
    const oldPriceElem = document.getElementById('old-price');
    const newPriceElem = document.getElementById('new-price');
    const descriptionElem = document.getElementById('description'); // Elemento para la descripción

    // Obtener los datos de sessionStorage
    const producto = sessionStorage.getItem('producto');
    const productImage = sessionStorage.getItem('imagen');
    const newPrice = sessionStorage.getItem('nuevoPrecio');
    const oldPrice = sessionStorage.getItem('antiguoPrecio');
    const discount = sessionStorage.getItem('descuento');
    const description = sessionStorage.getItem('descripcion');

    if (productImage) {
        img.src = productImage; // Cambia a la imagen especificada
    }

    if (producto) {
        title.textContent = producto; // Cambia el título
    }

    if (oldPrice) {
        oldPriceElem.textContent = oldPrice; // Cambia el precio antiguo
    }

    if (newPrice) {
        newPriceElem.textContent = newPrice; // Cambia el precio nuevo
    }

    if (description) {
        descriptionElem.textContent = description; // Cambia la descripción
    }

    if (discount) {
        document.getElementById('discount').textContent = discount; // Cambia el descuento
    }
});


//cambiar las imágenes al hacer clic 
document.querySelectorAll('.thumbnail').forEach(thumbnail => {
    thumbnail.addEventListener('click', function() {
        document.getElementById('main-product-image').src = this.src;
    });
});

//aumentar o disminuir la cantidad
const quantityInput = document.getElementById('quantity');
document.getElementById('increase-qty').addEventListener('click', function() {
    quantityInput.value = parseInt(quantityInput.value) + 1;
});

document.getElementById('decrease-qty').addEventListener('click', function() {
    if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
});

//"Añadir al carrito"
document.querySelector('.add-to-cart-btn').addEventListener('click', function() {
    alert('Producto añadido al carrito.');
});

//"Añadir a la lista de deseos"
document.querySelector('.wishlist-btn').addEventListener('click', function() {
    alert('Producto añadido a la lista de deseos.');
});

//"Comparar"
document.querySelector('.compare-btn').addEventListener('click', function() {
    alert('Producto añadido para comparación.');
});

// Acción del botón de volver arriba
window.onscroll = function() {
    const backToTopButton = document.getElementById('back-to-top');
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        backToTopButton.style.display = "block";
    } else {
        backToTopButton.style.display = "none";
    }
};

document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

function verDetalles(producto) {
    if (producto === 'Silla en Madera') {
        document.getElementById('main-product-image').src = 'images_ctg/D_NQ_NP_655928-MCO54766209176_032023-O.webp';
        //Imagenes prodcutos
        document.getElementById('product-title').textContent = 'Silla en Madera';
        document.getElementById('new-price').textContent = '$999.990';
        document.getElementById('old-price').textContent = '$2.299.990';
        document.getElementById('discount').textContent = '-60%';
        document.getElementById('description').textContent = 'Silla moderna en madera de alta calidad, perfecta para complementar la decoración de tu hogar. Ideal para cualquier espacio, ya sea comedor o sala.';
    } else if (producto === 'BaseCama Gris') {
        document.getElementById('main-product-image').src = 'images_ctg/basecama-sublim-ambiente.webp';
        document.getElementById('product-title').textContent = 'BaseCama Gris';
        document.getElementById('new-price').textContent = '$299.992';
        document.getElementById('old-price').textContent = '$399.990';
        document.getElementById('discount').textContent = '-25%';
        document.getElementById('description').textContent = 'BaseCama Gris de alta resistencia y diseño moderno, ideal para cualquier dormitorio.';
    } else if (producto === 'Armario') {
        document.getElementById('main-product-image').src = 'images_ctg/closet-armario-denver-aglomerado-moderno.webp';
        document.getElementById('product-title').textContent = 'Armario';
        document.getElementById('new-price').textContent = '$699.991';
        document.getElementById('old-price').textContent = '$799.990';
        document.getElementById('discount').textContent = '-13%';
        document.getElementById('description').textContent = 'Armario de diseño moderno con gran capacidad de almacenamiento. Ideal para optimizar el espacio en tu hogar.';
    } else if (producto === 'Comedor') {
        document.getElementById('main-product-image').src = 'images_ctg/images.jfif';
        document.getElementById('product-title').textContent = 'Comedor';
        document.getElementById('new-price').textContent = '$1.559.990';
        document.getElementById('old-price').textContent = '$2.399.990';
        document.getElementById('discount').textContent = '-35%';
        document.getElementById('description').textContent = 'Comedor de estilo contemporáneo, perfecto para reunir a la familia en cualquier ocasión.';
    } else if (producto === 'Juego de Sala') {
        document.getElementById('main-product-image').src = 'images_ctg/JUEGO-DE-SALA-AZUL-TERCIOPELO-COMPLETOc-scaled.jpg';
        document.getElementById('product-title').textContent = 'Juego de Sala';
        document.getElementById('new-price').textContent = '$899.991';
        document.getElementById('old-price').textContent = '$999.990';
        document.getElementById('discount').textContent = '-10%';
        document.getElementById('description').textContent = 'Juego de sala en terciopelo azul, elegante y confortable para tu sala de estar.';
    }
}

