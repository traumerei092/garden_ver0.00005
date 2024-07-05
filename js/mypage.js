document.addEventListener("DOMContentLoaded", function() {
    const profileBtn = document.getElementById("profile-btn");
    const sidebar = document.getElementById("sidebar");
    const closeBtn = document.getElementById("close-btn");
    const logoutLink = document.getElementById("logout-link");
    const deleteAccountLink = document.getElementById("delete-account-link");

    profileBtn.addEventListener("click", function() {
        sidebar.classList.add("active");
    });

    closeBtn.addEventListener("click", function() {
        sidebar.classList.remove("active");
    });

    logoutLink.addEventListener("click", function(e) {
        e.preventDefault();
        if (confirm("ログアウトしますか？")) {
            window.location.href = "logout.php";
        }
    });

    deleteAccountLink.addEventListener("click", function(e) {
        e.preventDefault();
        if (confirm("本当に退会しますか？")) {
            window.location.href = "delete_account.php";
        }
    });

    //mypageコンテンツの切り替え
    const myshopTag = document.getElementById("myshopTag");
    const communityTag = document.getElementById("communityTag");
    const talkroomTag = document.getElementById("talkroomTag");
    const hogeTag = document.getElementById("hogeTag");
    const fugaTag = document.getElementById("fugaTag");
    const myshopContainer = document.getElementById("myshopContainer");
    const communitiesContainer = document.getElementById("communitiesContainer");
    const talkroomContainer = document.getElementById("talkroomContainer");
    const hogeContainer = document.getElementById("hogeContainer");
    const fugaContainer = document.getElementById("fugaContainer");

    myshopTag.addEventListener("click", function () {
        myshopTag.classList.add("active");
        communityTag.classList.remove("active");
        talkroomTag.classList.remove("active");
        hogeTag.classList.remove("active");
        fugaTag.classList.remove("active");
        myshopContainer.classList.add("active");
        communitiesContainer.classList.remove("active");
        talkroomContainer.classList.remove("active");
        hogeContainer.classList.remove("active");
        fugaContainer.classList.remove("active");
    });

    communityTag.addEventListener("click", function () {
        myshopTag.classList.remove("active");
        communityTag.classList.add("active");
        talkroomTag.classList.remove("active");
        hogeTag.classList.remove("active");
        fugaTag.classList.remove("active");
        myshopContainer.classList.remove("active");
        communitiesContainer.classList.add("active");
        talkroomContainer.classList.remove("active");
        hogeContainer.classList.remove("active");
        fugaContainer.classList.remove("active");
    });

    talkroomTag.addEventListener("click", function () {
        myshopTag.classList.remove("active");
        communityTag.classList.remove("active");
        talkroomTag.classList.add("active");
        hogeTag.classList.remove("active");
        fugaTag.classList.remove("active");
        myshopContainer.classList.remove("active");
        communitiesContainer.classList.remove("active");
        talkroomContainer.classList.add("active");
        hogeContainer.classList.remove("active");
        fugaContainer.classList.remove("active");
    });

    hogeTag.addEventListener("click", function () {
        myshopTag.classList.remove("active");
        communityTag.classList.remove("active");
        talkroomTag.classList.remove("active");
        hogeTag.classList.add("active");
        fugaTag.classList.remove("active");
        myshopContainer.classList.remove("active");
        communitiesContainer.classList.remove("active");
        talkroomContainer.classList.remove("active");
        hogeContainer.classList.add("active");
        fugaContainer.classList.remove("active");
    });

    fugaTag.addEventListener("click", function () {
        myshopTag.classList.remove("active");
        communityTag.classList.remove("active");
        talkroomTag.classList.remove("active");
        hogeTag.classList.remove("active");
        fugaTag.classList.add("active");
        myshopContainer.classList.remove("active");
        communitiesContainer.classList.remove("active");
        talkroomContainer.classList.remove("active");
        hogeContainer.classList.remove("active");
        fugaContainer.classList.add("active");
    });

    // コミュニティ作成フォームの表示と非表示の切り替え
    const createCommunityButton = document.getElementById("createCommunityButton");
    const createCommunityForm = document.getElementById("createCommunityForm");
    const communityList = document.getElementById("communityList");
    const communityGrid = document.getElementById("communityGrid");

    createCommunityButton.addEventListener("click", function() {
        createCommunityForm.style.display = "block";
        communityList.style.display = "none";
        communityGrid.style.display = "none";
    });

    // リスト表示とカラム表示の切り替え
    document.getElementById('listView').addEventListener('click', function() {
        document.getElementById('communityList').classList.add("active");
        document.getElementById('communityGrid').classList.remove("active");
        document.getElementById("listView").classList.add("active");
        document.getElementById('gridView').classList.remove("active");
        document.getElementById('searchResults').classList.remove("active");
        createCommunityForm.style.display = "none";
    });

    document.getElementById('gridView').addEventListener('click', function() {
        document.getElementById('communityList').classList.remove("active");
        document.getElementById('communityGrid').classList.add("active");
        document.getElementById("listView").classList.remove("active");
        document.getElementById('gridView').classList.add("active");
        document.getElementById('searchResults').classList.remove("active");
        createCommunityForm.style.display = "none";
    });
});
