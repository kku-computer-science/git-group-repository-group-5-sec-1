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

TC004: Add Fund
    Select From List By Index    id=funds_type    2    
    Sleep    2s
    Select From List By Index    id=funds_category    18
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[3]/div/input    งบประมาณมหาวิทยาลัย ประจำปี พ.ศ. 2568
    Input Text    xpath=/html/body/div/div/div/div/div/div/div/div/form/div[4]/div/input    มหาวิทยาลัยขอนแก่น
    Click Element    xpath=/html/body/div/div/div/div/div/div/div/div/form/button
    Wait Until Page Contains Element    xpath=//p[contains(text(), 'เพิ่มทุนวิจัยสำเร็จ')]
    Page Should Contain Element    xpath=//p[contains(text(), 'เพิ่มทุนวิจัยสำเร็จ')]

TC005: Show Add Fund
    Scroll Element Into View    xpath=//*[@id="example1"]/tbody/tr[13]/td[5]/form/li[1]/a
    Click Element    xpath=//*[@id="example1"]/tbody/tr[13]/td[5]/form/li[1]/a
    Page Should Contain Element    xpath=//p[contains(text(), 'งบประมาณมหาวิทยาลัย ประจำปี พ.ศ. 2568')]
    Page Should Contain Element    xpath=//p[contains(text(), 'ทุนภายใน')]
    Page Should Contain Element    xpath=//p[contains(text(), 'งบประมาณมหาวิทยาลัย / วิจัยใหม่ / งานวิจัยพื้นฐาน')]
    Page Should Contain Element    xpath=//p[contains(text(), 'มหาวิทยาลัยขอนแก่น')]
    Page Should Contain Element    xpath=//p[contains(text(), 'จุฑารัตน์ อุ่มไธสง')]