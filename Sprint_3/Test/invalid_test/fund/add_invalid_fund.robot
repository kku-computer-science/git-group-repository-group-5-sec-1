*** Settings ***
Documentation
Resource    ../../resource/resource.robot
Suite Teardown    Close Browser

*** Test Cases ***

TC001: Open Login Page
    Open Browser To Login Page
    
TC002: Login Success
    Input Username and Password    username=jutaum@kku.ac.th   password=123456789
    Click Element    xpath=/html/body/div/div[2]/div[2]/form/div[4]/button  #Login button
    Page Should Contain    Dashboard

TC003: Go To Fund Page
    #Manage Fund
    Click Element    xpath=//*[@id="sidebar"]/ul/li[5]/a/span
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/a    #Button Add

# TC004: Add Fund by click fund_category
#     Select From List By Index   id=funds_category    1
#     Sleep    5s

# TC004: Add Fund without anything
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div[1]/strong    timeout=3s
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกประเภททุนวิจัย')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกลักษณะทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]

# TC005: Add Fund with only funds_type
#     Select From List By Index    id=funds_type    1    
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกลักษณะทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]

# TC006: Add Fund with funds_category
#     Select From List By Index    id=funds_type    0
#     Sleep    5s
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกประเภททุนวิจัย')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกลักษณะทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]

# TC007: Add Fund with only funds_name
#     Select From List By Index    id=funds_type    0    
#     Select From List By Index    id=funds_category    0
#     Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input    ทุนเพื่อพัฒนางานวิจัย
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกประเภททุนวิจัย')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกลักษณะทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]

# TC008: Add Fund with only funds_agency
#     Select From List By Index    id=funds_type    0    
#     Select From List By Index    id=funds_category    0
#     Clear Element Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input
#     Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[4]/div/input    INet
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Page Contains Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=/html/body/div/div/div/div/div/div[1]/strong
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกประเภททุนวิจัย')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณาเลือกลักษณะทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]

# TC009: Add Fund With funds_type and funds_category
#     Select From List By Index    id=funds_type    1
#     Select From List By Index    id=funds_category    1
#     Clear Element Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[4]/div/input
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]

TC004: Add Fund with funds_name
    Select From List By Index    id=funds_type    1
    Select From List By Index    id=funds_category    1
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input    ทุนเพื่อพัฒนางานวิจัย
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
    Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกหน่วยงานที่สนับสนุน')]
    Sleep    5s

# TC011: Add Fund with funds_agency
#     Clear Element Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input
#     Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[4]/div/input    INet
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Element Is Visible  xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]    timeout=3s
#     Page Should Contain Element    xpath=//li[contains(text(), 'กรุณากรอกชื่อทุน')]
# TC004: Add Fund
#     Select From List By Index    id=funds_type    1    
#     Sleep    2s
#     Select From List By Index    id=funds_category    2
#     Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input    ทุนเพื่อพัฒนางานวิจัย
#     Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[4]/div/input    INet
#     Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
#     Wait Until Page Contains Element    xpath=//p[contains(text(), 'เพิ่มทุนวิจัยสำเร็จ')]
#     Page Should Contain Element    xpath=//p[contains(text(), 'เพิ่มทุนวิจัยสำเร็จ')]
