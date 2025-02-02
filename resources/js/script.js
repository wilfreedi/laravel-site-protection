class UserInfoCollector {
    constructor() {
        this.apiEndpoint = '/site-protection-data'; // URL для отправки данных
        this.encryptionKey =  document.querySelector('meta[name="protection-key"]')?.getAttribute('content'); // Ключ для шифрования
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!this.encryptionKey || this.encryptionKey.length < 16 || !this.csrfToken) {
            throw new Error('Error');
        }
    }

    // Метод для сбора информации о пользователе
    collectData() {
        const navigatorData = {
            userAgent: navigator.userAgent, // Информация о браузере
            cookiesEnabled: navigator.cookieEnabled, // Включены ли куки
            language: navigator.language, // Язык браузера
            platform: navigator.platform, // Платформа (например, Win32, Linux)
        };

        const screenData = {
            width: window.screen.width, // Ширина экрана
            height: window.screen.height, // Высота экрана
            colorDepth: window.screen.colorDepth, // Глубина цвета
        };

        const timezoneData = {
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone, // Временная зона
            offset: new Date().getTimezoneOffset(), // Смещение в минутах от UTC
        };

        // Определение автоматизации браузера
        const isAutomated = this.detectAutomation();

        // Собираем все данные в единый объект
        const collectedData = {
            navigator: navigatorData,
            screen: screenData,
            timezone: timezoneData,
            isAutomated,
            timestamp: new Date().toISOString(), // Текущее время
        };

        return collectedData;
    }

    // Метод для определения автоматизации браузера
    detectAutomation() {
        return (
            navigator.webdriver || // Проверка WebDriver
            window.outerWidth === 0 || // Аномальное разрешение окна
            window.outerHeight === 0
        );
    }

    // Метод для шифрования данных (используем AES из Web Crypto API)
    async encryptData(data) {
        const encoder = new TextEncoder();
        const encodedData = encoder.encode(JSON.stringify(data));

        const key = await crypto.subtle.importKey(
            "raw",
            encoder.encode(this.encryptionKey),
            { name: "AES-GCM" },
            false,
            ["encrypt"]
        );

        const iv = crypto.getRandomValues(new Uint8Array(12)); // Генерация IV
        const encryptedBuffer = await crypto.subtle.encrypt(
            { name: "AES-GCM", iv },
            key,
            encodedData
        );

        const encryptedArray = new Uint8Array(encryptedBuffer);
        const tagLength = 16; // Длина тега GCM по умолчанию
        const encryptedDataOnly = encryptedArray.slice(0, -tagLength);
        const authTag = encryptedArray.slice(-tagLength);

        const encryptedObject = {
            mix: btoa(String.fromCharCode(...iv)),
            data: btoa(String.fromCharCode(...encryptedDataOnly)),
            message: btoa(String.fromCharCode(...authTag))
        };

        return { data: btoa(JSON.stringify(encryptedObject)) }; // Кодируем в Base64
    }

    // Метод для отправки данных на сервер
    async sendData() {
        const data = this.collectData();
        const encryptedData = await this.encryptData(data);

        await fetch(this.apiEndpoint, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
            },
            body: JSON.stringify(encryptedData),
        }).then((response) => response.json())
            .then((data) => {
                if(data.bot) {
                    window.location.reload();
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }
}

const collector = new UserInfoCollector();
collector.sendData().then(r => {})
