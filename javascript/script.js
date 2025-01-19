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


	// Получаем элементы мобильного меню
	const menuToggle = document.getElementById('menu-toggle');
	const mobileMenu = document.getElementById('mobile-menu');

	if (menuToggle && mobileMenu ) {
		menuToggle.addEventListener('click', (event) => {
			event.stopPropagation(); // предотвращаем всплытие события, чтобы не закрывать меню сразу после его открытия
			// Переключаем меню
			if (menuToggle.classList.contains('is-active')) {
				mobileMenu.classList.remove('-translate-x-[1700px]');
				mobileMenu.classList.add('translate-x-0');
				document.body.style.overflow = 'hidden'; // Отключаем прокрутку
			} else {
				mobileMenu.classList.remove('translate-x-0');
				mobileMenu.classList.add('-translate-x-[1700px]');
				document.body.style.overflow = ''; // Включаем прокрутку обратно
			}
			// Переключаем класс is-active у кнопки
			menuToggle.classList.toggle('is-active');
		});

		document.addEventListener('click', (event) => {
			// Проверяем, был ли клик вне меню и кнопки
			if (!mobileMenu.contains(event.target) && !menuToggle.contains(event.target)) {
				mobileMenu.classList.remove('translate-x-0');
				mobileMenu.classList.add('-translate-x-[1700px]');
				menuToggle.classList.add('is-active');
				document.body.style.overflow = ''; // Включаем прокрутку обратно
			}
		});
	}

	// Получаем элементы сайдбар
	const sidebarToggle = document.getElementById('sidebar-toggle');
	const sidebarMenu = document.getElementById('sidebar-menu');
	if (sidebarToggle && sidebarMenu) {
		sidebarToggle.addEventListener('click', (event) => {
			event.stopPropagation(); // предотвращаем всплытие события, чтобы не закрывать меню сразу после его открытия

			if (sidebarToggle.classList.contains('is-active')) {
				sidebarMenu.classList.remove('-translate-x-[1700px]');
				sidebarMenu.classList.add('translate-x-0');
			} else {
				sidebarMenu.classList.remove('translate-x-0');
				sidebarMenu.classList.add('-translate-x-[1700px]');
			}
			// Переключаем класс is-active у кнопки
			sidebarToggle.classList.toggle('is-active');
		});
		document.querySelectorAll('.close-filter-btn').forEach(button => {
			button.addEventListener('click', () => {
				sidebarToggle.classList.toggle('is-active');
				sidebarMenu.classList.remove('translate-x-0');
				sidebarMenu.classList.add('-translate-x-[1700px]');
			});
		});

		document.addEventListener('click', (event) => {
			// Проверяем, был ли клик вне меню и кнопки
			if (!sidebarMenu.contains(event.target) && !sidebarToggle.contains(event.target)) {
				sidebarMenu.classList.remove('translate-x-0');
				sidebarMenu.classList.add('-translate-x-[1700px]');
				sidebarToggle.classList.add('is-active');
			}
		});
	}


	const swiper_rev = new Swiper('.swiper_rev', {
		slidesPerView: 1.2, // Показывает два с половиной слайда
		spaceBetween: 20,   // Отступы между слайдами
		centeredSlides: false, // Убирает центровку активного слайда
		loop: true,          // Зацикливает слайды
		navigation: {
			nextEl: '.rev-button-next',
			prevEl: '.rev-button-prev',
		},
		breakpoints: {
			490: {
				slidesPerView: 3, // Показывает два с половиной слайда
				spaceBetween: 32,
			}
		},
	});
	// Обновление кнопок при инициализации
	swiper_rev.on('init', () => {
		document.querySelector('.rev-button-prev').classList.remove('swiper-button-disabled');
		document.querySelector('.rev-button-next').classList.remove('swiper-button-disabled');
	});

	swiper_rev.init();

	const swiper_gids = new Swiper('.swiper_gids', {
		slidesPerView: 1.2, // Показывает два с половиной слайда
		spaceBetween: 20,   // Отступы между слайдами
		centeredSlides: false, // Убирает центровку активного слайда
		loop: true,          // Зацикливает слайды
		navigation: {
			nextEl: '.gid-button-next',
			prevEl: '.gid-button-prev',
		},
		breakpoints: {
			490: {
				slidesPerView: 3.5, // Показывает два с половиной слайда
				spaceBetween: 32,
			}
		},
	});
	// Обновление кнопок при инициализации
	swiper_gids.on('init', () => {
		document.querySelector('.gid-button-prev').classList.remove('swiper-button-disabled');
		document.querySelector('.gid-button-next').classList.remove('swiper-button-disabled');
	});

	swiper_gids.init();

	const swiper = new Swiper('.swiper', {
		slidesPerView: 1.2, // Показывает два с половиной слайда
		spaceBetween: 20,   // Отступы между слайдами
		centeredSlides: false, // Убирает центровку активного слайда
		loop: true,          // Зацикливает слайды
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		breakpoints: {
			490: {
				slidesPerView: 3, // Показывает два с половиной слайда
				spaceBetween: 32,
			}
		},
	});

	// Обновление кнопок при инициализации
	swiper_gids.on('init', () => {
		document.querySelector('.swiper-button-prev').classList.remove('swiper-button-disabled');
		document.querySelector('.swiper-button-next').classList.remove('swiper-button-disabled');
	});

	// Сохраняем экземпляр Flatpickr

	let selectedDatesRange = null;
	const calendarInstance = flatpickr("#calendar", {
		inline: true,
		mode: "range",
		minDate: "today",
		dateFormat: "Y-m-d",
		locale: "ru", // Указываем код языка
		onChange: function(selectedDates, dateStr) {
			selectedDatesRange = {
				dates: selectedDates,
				range: dateStr
			};
		}
	});
	const okBtn = document.getElementById('okBtn');
	const cancelBtn = document.getElementById('cancelBtn');
	if(okBtn && cancelBtn) {
		// Кнопка "Ок"
		okBtn.addEventListener("click", function() {
			if (selectedDatesRange && selectedDatesRange.dates.length) {
				document.querySelector('input[name="dateForm"]').value = selectedDatesRange.range;
				document.querySelector('input[name="dateForm"]').dispatchEvent(new Event('change', { bubbles: true }));

			} else {
				alert("Пожалуйста, выберите диапазон дат!");
			}
		});

		// Кнопка "Отмена"
		cancelBtn.addEventListener("click", function() {
			selectedDatesRange = null; // Сбрасываем выбранные даты
			calendarInstance.clear();  // Используем метод .clear() на экземпляре Flatpickr
			clearForm();
			removeGetParams();
			document.querySelector('input[name="dateForm"]').value = null;
			document.querySelector('input[name="dateForm"]').dispatchEvent(new Event('change', { bubbles: true }))

			document.querySelectorAll('#cat_sidebar .flex.items-center a').forEach((link) => {
				const url = window.location.origin + window.location.pathname;
				link.href = url.toString();
			});
		});
	}

	function startCalendars() {
		document.querySelectorAll(".calendar-wrapper").forEach((wrapper) => {
			const calendarElement = wrapper.querySelector(".calendar");
			const selectedDatesAttr = wrapper.getAttribute("data-dates"); // Получаем даты из data-attr


			// Парсим даты, если они есть
			const selectedDates = selectedDatesAttr ? JSON.parse(selectedDatesAttr) : [];

			// Инициализация Flatpickr с установленными датами
			const calendarInstance = flatpickr(calendarElement, {
				inline: true,
				mode: "range",
				minDate: "today",
				dateFormat: "Y-m-d",
				locale: "ru", // Указываем код языка
				defaultDate: selectedDates, // Устанавливаем выбранные даты
				onChange: function (selectedDates, dateStr) {
					// Обработка изменения даты
					const selectedDatesRange = {
						dates: selectedDates,
						range: dateStr
					};

					console.log("Selected range:", selectedDatesRange);
				}
			});
		});
	}
	startCalendars();

	// Функция для открытия/закрытия dropdown
	document.addEventListener('click', function (event) {
		// Проверяем, кликнули ли по кнопке dropdown
		const button = event.target.closest('.dropdown-button');
		if (button) {
			const dropdownButtons = document.querySelectorAll('.dropdown-button');
			const dropdownMenus = document.querySelectorAll('.dropdown-menu');
			const index = Array.from(dropdownButtons).indexOf(button);
			const menu = dropdownMenus[index];

			if (!menu) return;

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
		}

		// Проверяем, кликнули ли по элементу внутри меню для закрытия
		const menuItem = event.target.closest('.dropdown-menu .item');
		if (menuItem) {
			const menu = menuItem.closest('.dropdown-menu');
			const button = document.querySelector(`.dropdown-button[aria-expanded="true"]`);
			if (menu && button) {
				menu.classList.add('hidden');
				button.setAttribute('aria-expanded', 'false');
			}
		}

		// Проверяем, кликнули ли по кнопке закрытия внутри меню
		const closeButton = event.target.closest('.dropdown-menu .close-menu');
		if (closeButton) {
			const menu = closeButton.closest('.dropdown-menu');
			const button = document.querySelector(`.dropdown-button[aria-expanded="true"]`);
			if (menu && button) {
				menu.classList.add('hidden');
				button.setAttribute('aria-expanded', 'false');
			}
		}
	});

	//filter excursion
	const categoryIdElem = document.getElementById('category_id');
	if(categoryIdElem) {
		const categoryId = document.getElementById('category_id').value;
		document.getElementById('filter-form').addEventListener('change', function() {
			loadPosts();
		});
		document.getElementById('sort_form').addEventListener('change', function() {
			loadPosts();
		});
		function loadPosts() {
			// Собираем массив значений выбранных чекбоксов
			const getCheckedValues = (name) => {
				return Array.from(document.querySelectorAll(`#filter-form input[name="${name}"]:checked, #sort_form input[name="${name}"]:checked`)).map(input => input.value);
			};
			const duration = getCheckedValues('duration');
			const price = getCheckedValues('price');
			const postsPerPage = -1
			//const grade = getCheckedValues('gradeAge');
			const grade_sort = getCheckedValues('grade_sort');
			const dateForm = document.querySelector('input[name="dateForm"]').value ?? null;

			const params = new URLSearchParams({
				//grade: JSON.stringify(grade),
				grade_sort: JSON.stringify(grade_sort),
				price: JSON.stringify(price),
				duration: JSON.stringify(duration),
				dateForm: dateForm,
				posts_per_page: postsPerPage,
				category_id: categoryId,
			});

			document.querySelectorAll('#cat_sidebar .flex.items-center a').forEach((link) => {
				const url = new URL(link.href);
				if (dateForm) url.searchParams.set('dateForm', dateForm);

				if (price) url.searchParams.set('price', JSON.stringify(price));
				if (duration) url.searchParams.set('duration', JSON.stringify(duration));
				link.href = url.toString();
			});

			fetch('/wp-json/my_namespace/v1/filter-posts/?' + params.toString())
				.then(response => {
					if (!response.ok) {
						throw new Error('Failed to fetch the template.');
					}
					return response.text();
				})
				.then(html => {
					document.getElementById('tours').innerHTML = html;
					document.getElementById('card_link').scrollIntoView({ block: "start", behavior: "smooth" });
					startCalendars();
					//adjustCardLayout();
					//adjustWishBtn();
					//sortExcursion();
				})
				.catch(error => console.error('Error loading posts:', error));
		}
	}

	function clearForm() {
		// Получаем форму
		const form = document.getElementById('filter-form');

		// Перебираем элементы формы
		Array.from(form.elements).forEach(element => {
			switch (element.type) {
				case 'text':
				case 'textarea':
				case 'password':
				case 'email':
				case 'url':
				case 'number':
				case 'search':
				case 'tel':
				case 'hidden':
					element.value = ''; // Очистка текстовых полей
					break;
				case 'checkbox':
				case 'radio':
					element.checked = false; // Сброс чекбоксов и радио-кнопок
					break;
				case 'select-one':
				case 'select-multiple':
					element.selectedIndex = -1; // Сброс селектов
					break;
				default:
					break;
			}
		});

	}
	function removeGetParams() {
		// Получаем текущий URL без параметров
		const url = window.location.origin + window.location.pathname;

		// Обновляем URL в адресной строке без перезагрузки страницы
		window.history.replaceState({}, document.title, url);
	}

	//форма отзывов
	const formContainer = document.querySelector('.reviews_form');
	if(formContainer) {

		//Работа с файлами
		const inputField = document.getElementById("inputField");
		const suggestionsBox = document.getElementById("suggestions");

		const dataItems = Array.from(document.querySelectorAll(".data-item"));

		function showAllSuggestions() {
			dataItems.forEach(item => {
				item.style.display = "block";
			});
			suggestionsBox.classList.remove("hidden");
		}

		function showSuggestions() {
			const query = inputField.value.toLowerCase();

			// Если поле ввода пустое, скрываем список
			if (query === "") {
				suggestionsBox.classList.add("hidden");
				return;
			}

			let hasMatches = false;

			// Фильтруем данные и обновляем отображение
			dataItems.forEach(item => {
				if (item.textContent.toLowerCase().includes(query)) {
					item.style.display = "block";
					hasMatches = true;
				} else {
					item.style.display = "none";
				}
			});

			// Если нет совпадений, скрываем список
			if (!hasMatches) {
				suggestionsBox.classList.add("hidden");
			} else {
				suggestionsBox.classList.remove("hidden");
			}
		}

		inputField.addEventListener("focus", showAllSuggestions);

		inputField.addEventListener("input", showSuggestions);

		dataItems.forEach(item => {
			item.addEventListener("click", () => {
				inputField.value = item.textContent;
				suggestionsBox.classList.add("hidden");
			});
		});


		// Удаление файлов
		document.body.addEventListener("click", function (event) {
			if (!event.target.closest('#inputField') && !event.target.closest('#suggestions')) {
				suggestionsBox.classList.add("hidden");
			}

			if (event.target.classList.contains("delete")) {
				const index = Array.from(document.querySelectorAll('.delete')).indexOf(event.target);
				console.log('Удаляем файл:', index);

				filestoupload.splice(index, 1); // Удаляем из массива
				event.target.parentElement.remove(); // Удаляем из DOM

				console.log('Files to upload:', filestoupload.length);
			}
		});

		let filestoupload = [];

		function previewFiles() {
			const preview = document.querySelector('#preview');
			const files = document.querySelector('input[type=file]').files;


			if (files) {
				Array.from(files).forEach(readAndPreview);
			}
		}

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
						divdel.className = 'delete font-bold cursor-pointer';
						divdel.textContent = 'X';

						div.className = 'fileprew flex gap-2 text-[#FF7A45] text-[14px] mb-2 items-center mt-4';
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

		document.getElementById('popup-input-file').addEventListener('change', function() {
			previewFiles();
		});



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
					const popup = document.querySelector('.popup[data-popup="popup-success-rev"]');
					if (popup) {
						popup.classList.remove("hidden");
					}
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

	// Отправка формы
	document.querySelectorAll(".send-letter").forEach(form => {
		form.addEventListener("submit", function(e) {
			e.preventDefault()

			const thisForm = e.target;
			const name = thisForm.getAttribute('data-api');
			const formData = new FormData(thisForm);
			const phoneField = thisForm.querySelector('input[name="tel"]');

			if (phoneField) { // Если поле существует
				const phone = formData.get("tel");

				if (!isValidPhone(phone)) { // Проверяем, если значение есть и оно некорректное
					phoneField.classList.add('border', 'border-[#FF7A45]');
					return false;
				} else if ( isValidPhone(phone)) { // Если поле пустое или значение валидное
					phoneField.classList.remove('border', 'border-[#FF7A45]');
				}
			}


			if(name) {
				fetch(`/wp-json/custom/v1/${name}`, {
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
						if (!data.success) {
							throw new Error(data.message || 'Ошибка при отправке формы');
						}
						thisForm.reset();
						closeModal();
						const popup = document.querySelector(".popup[data-popup='popup-success']");
						popup.classList.remove("hidden")
						return true;
					})
					.catch(error => {
						console.error('Ошибка:', error);
						alert(error.message || 'Что-то пошло не так');
					})
					.finally(() => {
						//document.querySelector('.page-loader').style.display = 'none';
					});

			}

		});
	});

	//popups
	document.querySelectorAll("[data-open]").forEach(button => {
		// Открываем popup по data-open
		button.addEventListener("click", function() {
			const popupName = button.getAttribute("data-open");
			const popup = document.querySelector(`.popup[data-popup="${popupName}"]`);
			if (popup) {
				popup.classList.remove("hidden");
			}
		});
	});

	function openModal(queryElement) {
		queryElement.classList.remove("hidden")
	}
	function closeModal() {
		document.querySelectorAll(".popup-close").forEach(closeButton => {
			const popup = closeButton.closest(".popup");
			if (popup) {
				popup.classList.add("hidden");
			}
		});
	}

	document.querySelectorAll(".popup-close").forEach(closeButton => {
		// Закрываем все popups по клику на кнопку close
		closeButton.addEventListener("click", function() {
			const popup = closeButton.closest(".popup");
			if (popup) {
				popup.classList.add("hidden");
			}
		});
	});

	// Закрытие popup по клику вне его
	document.querySelectorAll(".popup").forEach(popup => {
		popup.addEventListener("click", function(e) {
			if (e.target === popup) {
				popup.classList.add("hidden");
				const popupContainer = popup.querySelector(".popup-media-container");
				if (popupContainer) {
					popupContainer.innerHTML = "";
				}
			}
		});
	});








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
	const devContainer = document.getElementById('response');
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




});
