const form = document.forms[0];

function togglePassword(event) {
	const field = form.password;
	const isPasswordField = field.type === 'password';
	field.type = isPasswordField ? 'text' : 'password';
	event.target.textContent = isPasswordField ? 'visibility_off' : 'visibility';
}

function validateLogin(event) {
	if (!isValid(event.currentTarget)) {
		event.preventDefault();
	}
}

function isValid(form) {
	const name = form.elements.name.value;
	const password = form.elements.password.value;

	if (name === '') {
		alert('Username is required.');
		return false;
	}

	if (password === '') {
		alert('Password is required.');
		return false;
	}

	if (password.length < 8) {
		alert('Password must be at least 8 characters long.');
		return false;
	}
	if (!/[a-z]/.test(password) ||
		!/[A-Z]/.test(password) ||
		!/[0-9]/.test(password)) {
		alert('Password must contain at least one lowercase letter, one uppercase letter, and one number.');
		return false;
	}

	return true;
}

function clearRadios(event) {
	const radios = form.querySelectorAll('input[type="radio"]');
	radios.forEach(radio => radio.checked = false);
	event.preventDefault();
}

function confirmUncheckedRadios(event) {
	const radios = form.querySelectorAll('input[type="radio"][value="Present"]');
	if (!radios.length) return; // no radio buttons

	const radioNodes = [...radios].map(radio => form[radio.name]);
	const uncheckedRadios = radioNodes.filter(node => node.value === '');
	if (!uncheckedRadios.length) return; // all radios checked

	const confirmation = confirm(`${uncheckedRadios.length} students are unmarked. Do you want to submit?`);
	if (confirmation) return; // submit if confirmed to leave unchecked

	// highlight unchecked radios
	event.preventDefault();
	for (const node of uncheckedRadios) {
		node.forEach(radio => {
			const radioParent = radio.parentElement.parentElement;
			const classes = ['bg-red-300', 'rounded-md'];
			radioParent.classList.add(...classes);
			radioParent.addEventListener('change', () =>
				radioParent.classList.remove(...classes)
			, { once: true });
		});
	}
}

(function emptyTableHandle() {
	const emptyTables = document.querySelectorAll('table:not(:has(tbody > tr))');
	emptyTables.forEach(table => {
		const tbody = table.querySelector('tbody');
		const tr = document.createElement('tr');
		const td = document.createElement('td');

		td.textContent = 'No records found...';
		td.classList.add('text-center', 'py-32');
		td.colSpan = 999;

		tr.appendChild(td);
		tbody.appendChild(tr);
	});
})();

(function navHighlight() {
	const navBar = document.querySelector('nav');
	const navLinks = navBar?.children[1].querySelectorAll('a') || [];

	navLinks.forEach(navLink => {
		if (navLink.href === window.location.href.split('?')[0]) {
			navLink.classList.remove('text-white');
			navLink.className = navLink.className.replaceAll('hover:', '');
		}
	});
})();

(function dropdown() {
	const details = document.querySelectorAll('details');
	const eventMap = {
		mouseenter: true,
		mouseleave: false,
		focusin: true,
		focusout: false
	};

	details.forEach(detailsElem => {
		for (const [event, shouldOpen] of Object.entries(eventMap)) {
			detailsElem.addEventListener(event, () => detailsElem.open = shouldOpen)
		}
	});
})();

(function formAutofill() {
	if (form?.dataset.autofill !== 'true') return;

	const urlParams = new URL(window.location).searchParams;
	for (const [inputField, value] of urlParams) {
		if (form.elements[inputField]) {
			try {
				form[inputField].value = value;
				if (!form[inputField].checkValidity()) {
					form[inputField].value = '';
				}
			} catch {} // ignore
		}
	}
})();

(function showFilterInput() {
	if (!form?.elements.filterBy) return;

	let visibleInput = form.elements[form.elements.filterBy.value];
	visibleInput.parentElement.classList.remove('hidden');
	visibleInput.disabled = false;

	form.filterBy.forEach(radio => {
		radio.addEventListener('change', event => {
			visibleInput.parentElement.classList.add('hidden');
			visibleInput.disabled = true;
			visibleInput = form.elements[event.target.value];
			visibleInput.parentElement.classList.remove('hidden');
			visibleInput.disabled = false;
		});
	});
})();

(function bfCacheHandle() {
	window.addEventListener('pageshow', (event) => {
		if (event.persisted) {
			// purge cached page to avoid stale data
			const reloadDisabled = document.querySelector('[data-allowcache="true"]');
			if (!reloadDisabled) window.location.reload();
		}
	});
})();
