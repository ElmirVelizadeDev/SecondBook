const sidebar = document.getElementById("adminSidebar");
const main = document.querySelector(".main");
const toggle = document.getElementById("toggleSidebar");
const closeBtn = document.getElementById("closeSidebar");
const overlay = document.getElementById("sidebarOverlay");

function isDesktop() {
    return window.innerWidth >= 992;
}

function isSidebarOpen() {
    return sidebar && sidebar.classList.contains("show");
}

function setToggleState(isOpen) {
    if (!toggle) {
        return;
    }

    toggle.classList.toggle("is-active", isOpen);
    toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
}

function setOverlayState(isOpen) {
    if (!overlay) {
        return;
    }

    if (!isDesktop() && isOpen) {
        overlay.classList.add("show");
        overlay.setAttribute("aria-hidden", "false");
    } else {
        overlay.classList.remove("show");
        overlay.setAttribute("aria-hidden", "true");
    }
}

function openSidebar() {
    if (!sidebar) {
        return;
    }

    sidebar.classList.add("show");

    if (isDesktop()) {
        document.body.classList.remove("sidebar-open");
        document.body.classList.remove("sidebar-collapsed");
    } else {
        document.body.classList.add("sidebar-open");
        document.body.classList.remove("sidebar-collapsed");
    }

    setToggleState(true);
    setOverlayState(true);
}

function closeSidebar() {
    if (!sidebar) {
        return;
    }

    sidebar.classList.remove("show");
    document.body.classList.remove("sidebar-open");

    if (isDesktop()) {
        document.body.classList.add("sidebar-collapsed");
    } else {
        document.body.classList.remove("sidebar-collapsed");
    }

    setToggleState(false);
    setOverlayState(false);
}

function toggleSidebarMenu() {
    if (isSidebarOpen()) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function initSidebar() {
    if (!sidebar) {
        return;
    }

    if (isDesktop()) {
        sidebar.classList.add("show");
        document.body.classList.remove("sidebar-open");
        document.body.classList.remove("sidebar-collapsed");

        setToggleState(true);
        setOverlayState(false);
    } else {
        sidebar.classList.remove("show");
        document.body.classList.remove("sidebar-open");
        document.body.classList.remove("sidebar-collapsed");

        setToggleState(false);
        setOverlayState(false);
    }
}

/*
|--------------------------------------------------------------------------
| Toggle button
|--------------------------------------------------------------------------
*/

if (toggle) {
    toggle.addEventListener("click", function (event) {
        event.preventDefault();
        event.stopPropagation();

        toggleSidebarMenu();
    });
}

/*
|--------------------------------------------------------------------------
| Close button
|--------------------------------------------------------------------------
*/

if (closeBtn) {
    closeBtn.addEventListener("click", function (event) {
        event.preventDefault();
        event.stopPropagation();

        closeSidebar();
    });
}

/*
|--------------------------------------------------------------------------
| Mobile overlay
|--------------------------------------------------------------------------
*/

if (overlay) {
    overlay.addEventListener("click", function () {
        closeSidebar();
    });
}

/*
|--------------------------------------------------------------------------
| Escape
|--------------------------------------------------------------------------
*/

document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && isSidebarOpen() && !isDesktop()) {
        closeSidebar();
    }
});

/*
|--------------------------------------------------------------------------
| Prevent sidebar clicks from closing anything
|--------------------------------------------------------------------------
*/

if (sidebar) {
    sidebar.addEventListener("click", function (event) {
        event.stopPropagation();
    });
}

/*
|--------------------------------------------------------------------------
| Close mobile sidebar after clicking a navigation link
|--------------------------------------------------------------------------
*/

if (sidebar) {
    sidebar.querySelectorAll("a:not(.sb-toggle)").forEach(function (link) {
        link.addEventListener("click", function () {
            if (!isDesktop()) {
                closeSidebar();
            }
        });
    });
}

/*
|--------------------------------------------------------------------------
| Resize
|--------------------------------------------------------------------------
*/

let lastIsDesktop = isDesktop();

window.addEventListener("resize", function () {
    const nowDesktop = isDesktop();

    if (nowDesktop === lastIsDesktop) {
        return;
    }

    lastIsDesktop = nowDesktop;
    initSidebar();
});

/*
|--------------------------------------------------------------------------
| Initial state
|--------------------------------------------------------------------------
*/

initSidebar();
