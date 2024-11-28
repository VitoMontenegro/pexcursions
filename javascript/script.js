/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

document.addEventListener('DOMContentLoaded', function() {


	// Инициализируем Flatpickr для выбора даты
	const flatpickrElem = document.getElementById('datepicker');
	if (flatpickrElem) {
		flatpickr("#datepicker", {
			dateFormat: "Y-m-d", // Формат даты
			minDate: "today"
		});
	}

	// Функция для открытия/закрытия dropdown
	const dropdownButtons = document.querySelectorAll('.dropdown-button');
	const dropdownMenus = document.querySelectorAll('.dropdown-menu');
	if (dropdownButtons.length && dropdownMenus.length) {
		dropdownButtons.forEach((button, index) => {
			button.addEventListener('click', function () {
				const menu = dropdownMenus[index];
				const isExpanded = menu.classList.contains('hidden');
				const closeOnClick = button.getAttribute('data-close-on-click') === 'true';

				// Закрываем все меню, если они открыты
				dropdownMenus.forEach((otherMenu, otherIndex) => {
					if (otherIndex !== index) {
						otherMenu.classList.add('hidden');
						dropdownButtons[otherIndex].setAttribute('aria-expanded', 'false');
					}
				});

				// Переключаем текущее меню
				if (isExpanded) {
					menu.classList.remove('hidden');
					button.setAttribute('aria-expanded', 'true');
				} else {
					menu.classList.add('hidden');
					button.setAttribute('aria-expanded', 'false');
				}

				// Закрытие меню при клике на элемент внутри (если установлено)
				if (closeOnClick) {
					const menuItems = menu.querySelectorAll('.item');
					menuItems.forEach(item => {
						item.addEventListener('click', function () {
							menu.classList.add('hidden');
							button.setAttribute('aria-expanded', 'false');
						});
					});
				}
			});
		});

		// Функция для обновления текста кнопки фильтра
		function updateDropdownText(radio) {
			const dropdownText = radio.closest('.relative').querySelector('.dropdown-text');
			const span = radio.nextElementSibling; // Получаем <span> рядом с input
			if (dropdownText && span) {
				dropdownText.textContent = span.textContent; // Обновляем текст кнопки
			}
		}

		// Обработчик кликов по элементам с классом .change_text
		const changeTextElements = document.querySelectorAll('.change_text');
		changeTextElements.forEach(item => {
			item.addEventListener('click', function() {
				updateDropdownText(item); // Обновляем текст на кнопке при клике на элемент
			});
		});

		// Закрытие меню при клике вне его
		window.addEventListener('click', function (event) {
			if (!event.target.closest('.relative')) {
				dropdownMenus.forEach(menu => menu.classList.add('hidden'));
				dropdownButtons.forEach(button => button.setAttribute('aria-expanded', 'false'));
			}
		});
	}

	//filter excursion
	const categoryIdElem = document.getElementById('category_id');
	if(categoryIdElem) {
		const categoryId = document.getElementById('category_id').value;
		document.getElementById('filter-form').addEventListener('change', function() {
			console.log('form is changed!')
			getCardsForDate();
		});
		/*function loadPosts(page) {
			// Собираем массив значений выбранных чекбоксов
			const getCheckedValues = (name) => {
				return Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(input => input.value);
			};


			const date = document.getElementById("datepicker").value ?? '';
			const duration = getCheckedValues('duration') ?? '';
			const price = getCheckedValues('price') ?? '';
			const postsPerPage = 10
			const grade = getCheckedValues('grade' ?? '');
			console.log('date>>',date)
			console.log('duration>>',duration)
			console.log('price>>',price)
			console.log('postsPerPage>>',postsPerPage)
			console.log('grade>>',grade)

			const params = new URLSearchParams({
				grade: JSON.stringify(grade),
				price: JSON.stringify(price),
				duration,
				page,
				date,
				posts_per_page: postsPerPage,
				category_id: categoryId,
			});

			fetch('/wp-json/my_namespace/v1/filter-posts/?' + params.toString())
				.then(response => {
					if (!response.ok) {
						throw new Error('Failed to fetch the template.');
					}
					return response.text();
				})
				.then(html => {
					document.getElementById('response').innerHTML = html;
					const wishBtns = document.getElementById('posts-container').querySelectorAll('.wish-btn');
					if (wishButtons) {
						let currentProducts = getCookie('product');
						try {
							currentProducts = currentProducts ? JSON.parse(currentProducts) : [];
						} catch (e) {
							console.error("Error parsing cookies:", e);
							currentProducts = []; // Сбрасываем в пустой массив при ошибке
						}
						wishBtns.forEach(button => {
							const productId = button.getAttribute('data-wp-id');
							if (currentProducts.includes(productId)) {
								button.classList.add('active');
							}
						});
					}
				})
				.catch(error => console.error('Error loading posts:', error));
		}*/
		function getCardsForDate(arr = '') {

			const getCheckedValues = (name) => {
				const checkedRadio = document.querySelector(`input[name="${name}"]:checked`);
				return checkedRadio ? checkedRadio.value : '';  // Возвращаем строку или пустую строку
			};
			const contentTours = document.querySelector('.content__tours'); // блок с карточками туров
			const cards = document.querySelectorAll('.card'); // карточки туров
			const date = document.getElementById("datepicker").value ?? '';
			const duration = getCheckedValues('duration') ?? '';
			const haveSale = getCheckedValues('have_sale') ?? 0;
			const sorts = getCheckedValues('grade' ?? '');
			cards.forEach(card => card.style.display = 'block'); // Показываем все карточки

			cards.forEach(card => {
				if (haveSale) {
					console.log('haveSale>>',haveSale)
					if (!card.dataset.sale || card.dataset.sale !== '1') {
						card.style.display = 'none';
					}
				}
				if (duration) {
					const cardDuration = parseFloat(card.dataset.duration);
					console.log('duration>>',duration,cardDuration)
					if (duration === '3') {
						if (cardDuration > 3.5 || !cardDuration) card.style.display = 'none';
					} else if (duration === '5') {
						if (cardDuration <= 3 || cardDuration >= 5.5 || !cardDuration) card.style.display = 'none';
					} else if (duration === 'more5') {
						if (cardDuration < 5 || !cardDuration) card.style.display = 'none';
					}
				}
				if(sorts) {
					const cardsSort = Array.from(contentTours.getElementsByClassName('card'));
					cardsSort.sort((a, b) => {
						const costA = parseInt(a.getAttribute('data-cost'), 10);
						const costB = parseInt(b.getAttribute('data-cost'), 10);
						const costC = parseInt(a.getAttribute('data-popular'), 10);
						const costD = parseInt(b.getAttribute('data-popular'), 10);

						if (sorts === 'expensive') {
							return costA - costB; // От меньшего к большему
						} else if(sorts === 'chip') {
							return costB - costA; // От большего к меньшему
						} else {
							return costC - costD; // От большего к меньшему
						}
					});

					// Очистка контента перед добавлением отсортированных элементов
					contentTours.innerHTML = '';

					// Добавление отсортированных карточек обратно в контейнер
					cardsSort.forEach(card => contentTours.appendChild(card));
				}

			});

		}

	}




	//wishlist
	const wishButtons = document.querySelectorAll('.wish-btn');
	if (wishButtons) {
		let currentProducts = getCookie('product');
		try {
			currentProducts = currentProducts ? JSON.parse(currentProducts) : [];
		} catch (e) {
			console.error("Error parsing cookies:", e);
			currentProducts = []; // Сбрасываем в пустой массив при ошибке
		}

		wishButtons.forEach(button => {
			const productId = button.getAttribute('data-wp-id');

			// Добавляем класс active на кнопки, которые соответствуют продуктам в куки
			if (currentProducts.includes(productId)) {
				button.classList.add('active');
			}

		});
	}
	const devContainer = document.getElementById('posts-container');
	if (devContainer) {
		devContainer.addEventListener('click', (event) => {
			const button = event.target.closest('.wish-btn');
			if (!button) return; // Если клик не по wish-btn, выходим

			let currentProducts = getCookie('product');
			try {
				currentProducts = currentProducts ? JSON.parse(currentProducts) : [];
			} catch (e) {
				console.error("Error parsing cookies:", e);
				currentProducts = [];
			}

			const productId = button.getAttribute('data-wp-id');

			// Обновляем куки и класс active
			if (currentProducts.includes(productId)) {
				// Если продукт уже в куки, удаляем его
				currentProducts = currentProducts.filter(id => id !== productId);
				button.classList.remove('active');
			} else {
				// Если продукта нет в куки, добавляем его
				currentProducts.push(productId);
				button.classList.add('active');
			}

			// Сохраняем обновленные куки
			setCookie('product', JSON.stringify(currentProducts), 7);
		});
	}







	// Функция получения куки
	function getCookie(name) {
		const cookieArr = document.cookie.split(";");
		for (let i = 0; i < cookieArr.length; i++) {
			let cookie = cookieArr[i].trim();
			if (cookie.startsWith(name + "=")) {
				return cookie.substring(name.length + 1);
			}
		}
		return null;
	}

	// Функция установки куки
	function setCookie(name, value, days) {
		const date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		const expires = "expires=" + date.toUTCString();
		document.cookie = name + "=" + value + ";" + expires + ";path=/";
	}

	//Форма отзыва
	let filestoupload = [];
	function previewFiles() {
		const preview = document.querySelector('#preview');
		const files = document.querySelector('input[type=file]').files;

		function readAndPreview(file) {
			// Убедимся, что файл имеет допустимое расширение
			if (/\.(jpe?g|png|gif)$/i.test(file.name)) {
				const reader = new FileReader();

				if (file.size > 5242880) {
					alert('Максимальный размер файла не может превышать 5Мб');
				} else {
					reader.addEventListener("load", function () {
						const image = new Image();
						const z = document.createElement('div');
						z.textContent = file.name;

						image.height = 100;
						image.title = file.name;
						image.src = this.result;

						filestoupload.push(file); // Добавляем файл, а не base64

						const div = document.createElement('div');
						const divdel = document.createElement('div');
						divdel.className = 'delete';
						divdel.textContent = 'X';

						div.className = 'fileprew';
						div.appendChild(divdel);
						div.appendChild(z);
						preview.appendChild(div);

						console.log('Files to upload:', filestoupload.length);
						console.log('File name:', file.name);
						console.log('File size:', file.size);
					}, false);

					reader.readAsDataURL(file);
				}
			}
		}

		if (files) {
			Array.from(files).forEach(readAndPreview);
		}
	}

	// Удаление файлов
	document.body.addEventListener("click", function (event) {
		if (event.target.classList.contains("delete")) {
			const index = Array.from(document.querySelectorAll('.delete')).indexOf(event.target);
			console.log('Удаляем файл:', index);

			filestoupload.splice(index, 1); // Удаляем из массива
			event.target.parentElement.remove(); // Удаляем из DOM

			console.log('Files to upload:', filestoupload.length);
		}
	});

	const formContainer = document.querySelector('.reviews_form');
	if(formContainer) {
		formContainer.addEventListener('submit', function (e) {
			e.preventDefault();

			const thisForm = e.target;
			const name = thisForm.querySelector('[name=name]').value;
			const formData = new FormData(thisForm);

			// Добавляем файлы из filestoupload
			filestoupload.forEach((file, index) => {
				formData.append(`file[${index}]`, file);
			});

			if (!name) {
				const nameField = document.querySelector('.name_field input');
				nameField.classList.add('alert');
				window.scrollTo({
					top: document.querySelector('.reviews_form').offsetTop - 50,
					behavior: 'smooth'
				});
				return;
			}

			//document.querySelector('.page-loader').style.display = 'block';

			fetch('/wp-json/custom/v1/reviews-form', {
				method: 'POST',
				headers: {
					// Убедитесь, что REST API доступен без авторизации или передайте токен авторизации.
					//'X-WP-Nonce': wpApiSettings.nonce // Если требуется авторизация
				},
				body: formData
			})
				.then(response => {
					if (!response.ok) {
						return response.json().then(err => {
							throw new Error(err.message || 'Ошибка при отправке формы');
						});
					}
					return response.json();
				})
				.then(data => {
					console.log('Ответ сервера:', data);
					// document.querySelectorAll('.modal').forEach(modal => modal.style.display = 'none');
					// document.body.style.overflow = 'visible';
					// closeModal();
					// openModal(document.querySelector('.modal__reviews--success'));
				})
				.catch(error => {
					console.error('Ошибка:', error);
					alert(error.message || 'Что-то пошло не так');
				})
				.finally(() => {
					//document.querySelector('.page-loader').style.display = 'none';
				});
		});
	}



});
