import matplotlib.pyplot as plt
import sys


def draw_graph(list_date, list_aver, output_file):
    # x축 설정
    x = list_date

    # y축 설정
    y = list_aver

    # 그래프 그리기
    plt.figure(figsize=(10, 6))
    plt.plot(x, y, marker='o', linestyle='-', color='b')
    plt.xticks(rotation=45, ha='right')
    plt.xlabel('Datetime')
    plt.ylabel('Average Value')
    plt.title('Graph Title')
    plt.grid(True)
    plt.tight_layout()

    # 그래프를 이미지로 저장
    plt.savefig(output_file)


if __name__ == "__main__":
    if len(sys.argv) != 4:
        print("Usage: python draw_graph2.py <list_date> <list_aver> <output_file>")
        sys.exit(1)

    # 인자로 전달된 JSON 데이터를 파싱하여 리스트로 변환
    list_date_json = sys.argv[1]
    list_aver_json = sys.argv[2]
    list_date = eval(list_date_json)
    list_aver = eval(list_aver_json)

    # 출력 파일 경로
    output_file_path = sys.argv[3]

    # 그래프를 그리고 이미지로 저장
    draw_graph(list_date, list_aver, output_file_path)
