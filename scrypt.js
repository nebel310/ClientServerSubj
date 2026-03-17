let cart = [];

const products = document.querySelectorAll('.product');
const cartItemsContainer = document.querySelector('.cart-items');
const cartTotal = document.querySelector('.cart-total');
const checkoutButton = document.querySelector('.checkout');
const clearCartButton = document.querySelector('.clear-cart');
const categorySelect = document.getElementById('category');

const addToCart = (product) => {
    cart.push(product);
    renderCart();
};

const renderCart = () => {
    cartItemsContainer.innerHTML = '';
    let total = 0;
    cart.forEach((item, index) => {
        total += item.price;
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');
        cartItem.innerHTML = `
            <span>${item.name} - ${item.price} руб.</span>
            <button class="remove-item" data-index="${index}">Удалить</button>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
    cartTotal.textContent = 'Итого: ' + total + ' руб.';
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', (e) => {
            const index = e.target.dataset.index;
            cart.splice(index, 1);
            renderCart();
        });
    });
};

const clearCart = () => {
    cart = [];
    renderCart();
};

const checkout = () => {
    if (cart.length === 0) {
        alert('Корзина пуста');
    } else {
        alert('Оплата прошла успешно!');
        clearCart();
    }
};

products.forEach(product => {
    const button = product.querySelector('.add-to-cart');
    button.addEventListener('click', () => {
        const name = product.querySelector('a').innerText.split('\n')[1]?.trim() || product.querySelector('a').innerText.trim();
        const price = Number(product.dataset.price);
        const category = product.dataset.category;
        addToCart({ name, price, category });
    });
});

checkoutButton.addEventListener('click', checkout);
clearCartButton.addEventListener('click', clearCart);

categorySelect.addEventListener('change', (e) => {
    const selected = e.target.value;
    products.forEach(product => {
        if (selected === 'all' || product.dataset.category === selected) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
});