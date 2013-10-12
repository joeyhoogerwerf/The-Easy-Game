Alarm

cseg	segment para public 'code'
org	100h
alarm	proc far

; Memory-resident program to intercept the timer interrupt.

intaddr equ 1ch*4
segaddr equ 62h*4
mfactor equ 17478
whozat	equ 1234h
color	equ 14h 

	assume cs:cseg,ds:cseg,ss:nothing,es:nothing
	jmp p150

jumpval dd 0
signature dw whozat
state	db 0
wait	dw 18
hour	dw 0
atime	dw 0ffffh
acount	dw 0
atone	db 5

aleng	dw 8080h

dhours	dw 0
	db ':'
dmins	dw 0
	db ':'
dsecs	dw 0
	db '-'
ampm	db 0
	db 'm'

tstack	db 16 dup('stack   ')
estack	db 0
holdsp	dw 0
holdss	dw 0

p000:
	push ax
	push ds
	pushf

	push cs
	pop ds
	mov ax,wait
	dec ax
	jz p010
	mov wait,ax
	jmp p080

p010:	cli
	mov ax,ss
	mov holdss,ax
	mov holdsp,sp
	mov ax,ds
	mov ss,ax
	mov sp,offset estack
	sti

	push bx
	push cx
	push dx
	push es
	push si
	push di
	push bp

	mov ax,18
	mov wait,ax

	mov al,state
	cmp al,'-'
	jnz p015
	jmp p070

p015:	mov ah,0
	int 1ah
	mov ax,dx
	mov dx,cx
	mov cl,4
	shl dx,cl
	mov bx,ax
	mov cl,12
	shr bx,cl
	add dx,bx
	mov cl,4
	shl ax,cl
	mov bx,mfactor
	div bx
	cmp ax,atime
	jnz p020
	call p100
	push ax
	mov ax,acount
	dec ax
	mov acount,ax
	cmp ax,0
	jnz p018
	mov ax,0ffffh
	mov atime,ax
p018:	pop ax

copyr	db 'Alarm - Clock',10,13,'$'
msg1	db 'Invalid time - must be from 00:00 to 23:59',10,13,'$'
msg2	db 'Resetting alarm time',10,13,'$'
msg3	db 'Turning clock display off',10,13,'$'

alarm	endp
cseg	ends
end	alarm