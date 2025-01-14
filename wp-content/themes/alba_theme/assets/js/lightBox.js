document.addEventListener("DOMContentLoaded", function () {
  // Create lightbox element
  const lightbox = document.createElement("div");
  lightbox.classList.add("lightbox");
  document.body.appendChild(lightbox);

  // Create lightbox content container
  const lightboxContent = document.createElement("div");
  lightboxContent.classList.add("lightbox-content");
  lightbox.appendChild(lightboxContent);

  // Create close button
  const closeButton = document.createElement("div");
  closeButton.classList.add("lightbox-close");
  closeButton.innerHTML = "&times;";
  lightboxContent.appendChild(closeButton);

  // Create image element
  const lightboxImage = document.createElement("img");
  lightboxImage.classList.add("lightbox-image");
  lightboxContent.appendChild(lightboxImage);

  // Function to open lightbox
  function openLightbox(imageSrc) {
    lightboxImage.src = imageSrc;
    // Set maxWidth to 80% of viewport width
    lightboxImage.style.maxHeight = "60vh";
    lightbox.style.opacity = 0;
    lightbox.classList.add("show");
    void lightbox.offsetWidth; // Trigger reflow
    lightbox.style.opacity = 1;
  }

  // Function to close lightbox
  function closeLightbox() {
    lightbox.style.opacity = "0";
    lightboxContent.style.animation = "zoomFade 0.3s ease-out reverse";

    setTimeout(() => {
      lightbox.classList.remove("show");
      lightboxContent.style.animation = "";
    }, 300);
  }

  // Add click event to all images you want to make clickable
  document.querySelectorAll(".lightbox-trigger").forEach((img) => {
    img.addEventListener("click", function () {
      // Get the full-size image URL
      const fullSizeUrl = this.getAttribute("data-full-size");
      openLightbox(fullSizeUrl);
    });
  });

  // Close lightbox when clicking outside image or on close button
  lightbox.addEventListener("click", function (e) {
    if (e.target === lightbox) {
      closeLightbox();
    }
  });

  closeButton.addEventListener("click", closeLightbox);

  // Close lightbox with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && lightbox.classList.contains("show")) {
      closeLightbox();
    }
  });
});
