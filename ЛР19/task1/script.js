document.addEventListener("DOMContentLoaded", function() {
    const catalogList = document.getElementById("catalog-list");
    const cartItems = document.getElementById("cart-items");

    let data; // Добавьте определение data

    // Загрузка товаров из JSON-файла
    fetch("products.json")
        .then(response => response.json())
        .then(products => {
            data = products; // Присвойте данные переменной data
            data.forEach(product => {
                const productElement = document.createElement("div");
                productElement.classList.add("catalog-item");
                productElement.innerHTML = `
                    <h3>${product.name}</h3>
                    <p>Цена: $${product.price.toFixed(2)}</p>
                    <button onclick="addToCart(${product.id})">Добавить в корзину</button>
                `;
                catalogList.appendChild(productElement);
            });
        });

    const cart = [];

    // Добавление товара в корзину
    window.addToCart = function(productId) {
        const product = data.find(product => product.id === productId);
        cart.push(product);
        renderCart();
    };

    // Отрисовка корзины
    function renderCart() {
        cartItems.innerHTML = "";
        cart.forEach(product => {
            const cartItem = document.createElement("div");
            cartItem.classList.add("cart-item");
            cartItem.innerHTML = `
                <p>${product.name} - $${product.price.toFixed(2)}</p>
            `;
            cartItems.appendChild(cartItem);
        });
    }
});
