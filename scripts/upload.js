toast = document.querySelector(".toast");

function showToast() {
	toast.classList.remove("hidden_element");
	setTimeout(() => {
		toast.classList.add("hidden_element");
	}, 1500);
}

let copyAddress = document.getElementById("copyButton");

copyAddress.addEventListener("click", () => {
	showToast();
});
