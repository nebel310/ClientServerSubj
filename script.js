document.addEventListener('DOMContentLoaded', () => {
  let basket = [];


  const items = document.querySelectorAll('.item');
  const filter = document.getElementById('filter');
  const list = document.getElementById('cart-items');
  const total = document.getElementById('total');
  const payBtn = document.getElementById('pay');
  const clearBtn = document.getElementById('clear');

  const saveBasket = () => {
    localStorage.setItem('cart', JSON.stringify(basket));
  };

  const updateBasket = () => {
    list.innerHTML = '';
    let sum = 0;
    basket.forEach((item, index) => {
      const li = document.createElement('li');
      li.innerHTML = `${item.name} - ${item.price} руб. <button class="remove_btn" data-index="${index}">Удалить</button>`;
      list.appendChild(li);
      sum += item.price;
    });
    total.textContent = `Итого: ${sum} руб.`;
    saveBasket();

    document.querySelectorAll('.remove_btn').forEach(btn => {
      btn.addEventListener('click', e => {
        const i = e.target.dataset.index;
        basket.splice(i, 1);
        updateBasket();
      });
    });
  };


  const addItem = (name, price) => {
    basket.push({ name, price: Number(price) });
    updateBasket();
  };



  document.querySelectorAll('.add_btn').forEach(btn => {
    btn.addEventListener('click', e => {
      addItem(e.target.dataset.name, e.target.dataset.price);
    });
  });


  filter.addEventListener('change', e => {
    const cat = e.target.value;
    items.forEach(item => {
      item.style.display = (cat === 'all' || item.dataset.category === cat) ? 'block' : 'none';
    });
  });

  payBtn.addEventListener('click', () => {
    if (basket.length === 0) {
      alert('Корзина пуста');
    } else {
      alert('Покупка прошла успешно!');
      basket = [];
      updateBasket();
    }
  });

  
  clearBtn.addEventListener('click', () => {
    basket = [];
    updateBasket();
  });

  
  const savedCart = localStorage.getItem('cart');
  if (savedCart) {
    basket = JSON.parse(savedCart);
    updateBasket();
  }
});